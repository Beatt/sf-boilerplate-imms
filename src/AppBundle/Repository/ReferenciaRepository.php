<?php


namespace AppBundle\Repository;


use Carbon\Carbon;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\DBAL\Statement;
use Doctrine\ORM\EntityManager;

class ReferenciaRepository
{
    private $entityManager;

    public function __construct(ObjectManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function paginate($perPage = 10, $offset = 1, $filters = [])
    {
        $query = $this->createQuery($perPage, $offset, $filters);
//        echo "{$query}\n";
        $queryTotal = $this->createTotalQuery($filters);
        /** @var Statement $statement */
        $statement = $this->entityManager->getConnection()->prepare($query);
        $statement2 = $this->entityManager->getConnection()->prepare($queryTotal);
        $this->bindParams($statement, $filters, $perPage, $offset);
        $this->bindParams($statement2, $filters);
        $statement->execute();
        $statement2->execute();
        return ['data' => $statement->fetchAll(),
            'total' => $statement2->fetchAll()[0]['total']
        ];
    }

    public function getYears()
    {
        $em = $this->entityManager;
        $RAW_QUERY = 'select extract(YEAR from fecha_pago) as year from pago where fecha_pago is not null group by 1 order by 1 desc;';
        $statement = $em->getConnection()->prepare($RAW_QUERY);
        $statement->execute();
        return $statement->fetchAll();
    }

    /**
     * @param $perPage
     * @param $offset
     * @param $filters
     * @return string
     */
    private function createQuery($perPage, $offset, $filters)
    {
        $sql = "select " .
            "max(pago.id) id," .
            "delegacion.nombre delegacion," .
            "institucion.nombre institucion," .
            "solicitud.no_solicitud," .
            "(CASE  when solicitud.tipo_pago = 'Único' then max(solicitud.monto) else max(campo_clinico.monto) end) monto," .
            "pago.referencia_bancaria," .
            "factura.id factura_id," .
            "factura.folio factura_folio," .
            "(select fecha_pago from pago paux where paux.id = max(pago.id) ) as fecha_pago, " .
            "(select validado from pago paux where paux.id = max(pago.id) ) as validado, ".
            "(select factura_generada from pago  paux where paux.id = max(pago.id) ) as factura_generada,  ".
            "(select requiere_factura from pago  paux where paux.id = max(pago.id) ) as requiere_factura ".
            $this->addRelations();

        $sql=$this->addFilters($sql, $filters);
        $sql.=" group by pago.referencia_bancaria, delegacion.nombre, solicitud.no_solicitud, 4,institucion.nombre, pago.referencia_bancaria, factura.id, factura.folio, solicitud.tipo_pago ";
        $sql.=$this->addOrders($filters);
        $sql.="LIMIT :limit OFFSET :offset ";
        return $sql;
    }

    private function bindParams(Statement &$statement, $filters = [], $perPage = null, $offset = null)
    {
        if(isset($filters['institucion']) && $filters['institucion']){
                $statement->bindValue('institucion', '%'.$filters['institucion'].'%');
        }

        if(isset($filters['delegacion']) && $filters['delegacion']){
            $statement->bindValue('delegacion', '%'.$filters['delegacion'].'%');
        }

        if(isset($filters['referencia']) && $filters['referencia']){
            $statement->bindValue('referencia', '%'.$filters['referencia'].'%');
        }

        if(isset($filters['factura']) && $filters['factura']){
            $statement->bindValue('factura', '%'.$filters['factura'].'%');
        }

        if(isset($filters['no_solicitud']) && $filters['no_solicitud']){
            $statement->bindValue('no_solicitud', '%'.$filters['no_solicitud'].'%');
        }

        if(isset($filters['monto']) && $filters['monto'] && is_numeric($filters['monto'])){
            $statement->bindValue('monto', '%'.$filters['monto'].'%');
        }

        if(!isset($filters['year']) || !$filters['year']) {
            $filters['year'] = Carbon::now()->format('Y');
        }
        $fecha_i = "{$filters['year']}-01-01";
        $fecha_f = "{$filters['year']}-12-31";
        $statement->bindValue('fecha_i', $fecha_i);
        $statement->bindValue('fecha_f', $fecha_f);
        if($perPage && is_numeric($perPage)){
            $statement->bindValue('limit', $perPage);
            if($offset && is_numeric($offset)){
                $statement->bindValue('offset',($offset-1) * $perPage);
            }
        }
    }

    private function addFilters($sql, $filters)
    {
        $sql.=" where pago.fecha_pago is not null ";

        if(isset($filters['institucion']) && $filters['institucion']){
            $sql.=(' AND upper(unaccent(institucion.nombre)) like UPPER(unaccent(:institucion))');
        }

        if(isset($filters['delegacion']) && $filters['delegacion']){
            $sql.=(' AND upper(unaccent(delegacion.nombre)) like UPPER(unaccent(:delegacion))');
        }

        if(isset($filters['referencia']) && $filters['referencia']){
            $sql.=(' AND upper(unaccent(pago.referencia_bancaria)) like UPPER(unaccent(:referencia))');
        }

        if(isset($filters['factura']) && $filters['factura']){
            $sql.=(' AND upper(unaccent(factura.folio)) like UPPER(unaccent(:factura))');
        }

        if(isset($filters['no_solicitud']) && $filters['no_solicitud']){
            $sql.=(' AND upper(unaccent(solicitud.no_solicitud)) like UPPER(unaccent(:no_solicitud))');
        }

        if(isset($filters['monto']) && $filters['monto'] && is_numeric($filters['monto'])){
            $sql.=" AND ((solicitud.tipo_pago = 'Único' AND concat(solicitud.monto,'')  like :monto) OR (solicitud.tipo_pago = 'Multiple' AND concat(campo_clinico.monto, '') like :monto) )";
        }

        if(isset($filters['estado']) && $filters['estado']){
            switch ($filters['estado']) {
                case 'a':
                    $sql.=(' AND pago.validado is null');
                    break;
                case 'b':
                    $sql.=(' AND pago.validado = true AND ((pago.requiere_factura = true AND factura.id is NOT NULL) OR (pago.requiere_factura = false) )');
                    break;
                case 'c':
                    $sql.=(' AND pago.validado = true AND pago.requiere_factura = true AND factura.id is NULL');
                    break;
                case 'd':
                    $sql.=(' AND pago.validado = false');
                    break;
            }
        }
        $sql.=(' AND pago.fecha_pago >= :fecha_i AND pago.fecha_pago <= :fecha_f');
        return $sql;
    }

    /**
     * @param array $filters
     * @return string
     */
    private function createTotalQuery(array $filters)
    {
        $sql = "select " .
            "count(distinct pago.referencia_bancaria) total " .
            $this->addRelations();
        $sql=$this->addFilters($sql, $filters);
        return $sql;
    }

    private function addRelations()
    {
        return  "from pago " .
            "inner join solicitud  on pago.solicitud_id = solicitud.id " .
            "inner join campo_clinico  on solicitud.id = campo_clinico.solicitud_id " .
            "inner join convenio on campo_clinico.convenio_id = convenio.id " .
            "inner join delegacion on convenio.delegacion_id = delegacion.id " .
            "inner join institucion on convenio.institucion_id = institucion.id " .
            "left join factura on pago.factura_id = factura.id ";
    }

    /**
     * @param $filters
     * @return string
     */
    private function addOrders($filters)
    {
        $sql = ' ORDER BY ';
        if(isset($filters['orderby']) && $filters['orderby']) {
            switch ($filters['orderby']){
                case 'a':
                    $sql .= '9 DESC '; //es el numero de la columna
                    break;
                case 'b':
                    $sql .= '9 ASC '; //es el numero de la columna
                    break;
                case 'c':
                    $sql .= 'solicitud.no_solicitud DESC ';
                    break;
                case 'd':
                default:
                $sql .= 'solicitud.no_solicitud ASC ';
                    break;
            }
        }else{
            $sql .= '1 DESC '; //es el numero de la columna
        }
        return $sql;
    }
}