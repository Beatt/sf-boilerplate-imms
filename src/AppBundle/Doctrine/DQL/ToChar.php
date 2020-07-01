<?php

namespace AppBundle\Doctrine\DQL;

use Doctrine\ORM\Query\AST\Functions\FunctionNode;
use Doctrine\ORM\Query\Parser;
use Doctrine\ORM\Query\Lexer;
use Doctrine\ORM\Query\SqlWalker;

// src:
// https://stackoverflow.com/questions/50890053/doctrine2-year-month-day-or-date-format-for-postgresql
class ToChar extends FunctionNode
{
  public $timestamp = null;
  public $pattern = null;

  // This tells Doctrine's Lexer how to parse the expression:
  public function parse(Parser $parser)
  {
    $parser->match(Lexer::T_IDENTIFIER);
    $parser->match(Lexer::T_OPEN_PARENTHESIS);
    $this->timestamp = $parser->ArithmeticPrimary();
    $parser->match(Lexer::T_COMMA);
    $this->pattern = $parser->ArithmeticPrimary();
    $parser->match(Lexer::T_CLOSE_PARENTHESIS);
  }

  // This tells Doctrine how to create SQL from the expression - namely by (basically) keeping it as is:
  public function getSql(SqlWalker $sqlWalker)
  {
    return 'to_char('.$this->timestamp->dispatch($sqlWalker) . ', ' . $this->pattern->dispatch($sqlWalker) . ')';
  }
}
