<?php


namespace AppBundle\Twig;
use Twig\Extension\AbstractExtension;
use Endroid\QrCode\Factory\QrCodeFactory;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\Routing\RouterInterface;
use Twig\TwigFunction;

class CampoClinicoQrExtension  extends AbstractExtension implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    public function getFunctions()
    {
        return [
            new TwigFunction('cc_qrencode', [$this, 'qrEncode']),
            new TwigFunction('cc_qrencode_uri', [$this, 'qrcodeDataUriFunction']),
        ];
    }

    public function qrEncode($url, $campo_clinico_id, $index)
    {
        $params = [];
        $params['extension'] = 'png';
        $params['text'] = $url . base64_encode($campo_clinico_id . ': '. $index);
        return $this->getRouter()->generate('endroid_qrcode', $params);
    }

    /**
     * Creates the QR code data corresponding to the given message.
     *
     * @param string $text
     * @param array  $options
     *
     * @return string
     */
    public function qrcodeDataUriFunction($url, $campo_clinico_id, $index, array $options = [])
    {
        $options['extension'] = 'png';
        $text = $url . base64_encode($campo_clinico_id . ': '. $index);
        $qrCode = $this->getQrCodeFactory()->createQrCode($options);
        $qrCode->setText($text);
        return $qrCode->getDataUri();
    }



    /**
     * Returns the router.
     *
     * @return RouterInterface
     */
    protected function getRouter()
    {
        return $this->container->get('router');
    }

    /**
     * Returns the QR code factory.
     *
     * @return QrCodeFactory
     */
    protected function getQrCodeFactory()
    {
        return $this->container->get('endroid.qrcode.factory');
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'app.camplo_clinico_qr_extension';
    }
}