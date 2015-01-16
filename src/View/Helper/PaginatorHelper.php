<?php
namespace RitaTools\View\Helper;




class PaginatorHelper extends \Cake\View\Helper\PaginatorHelper
{
    
    protected $_defaultConfig = [
        'options' => [],
        'templates' => [
            'nextActive' => '<li class="page-next"><a class="btn btn-action" rel="next" href="{{url}}">{{text}}</a></li>',
            'nextDisabled' => '<li class="page-next disabled"><a class="btn btn-action disabled" href="">{{text}}</a></li>',
            'prevActive' => '<li class="page-prev"><a <a class="btn btn-action " rel="prev" href="{{url}}">{{text}}</a></li>',
            'prevDisabled' => '<li class="page-prev  disabled"><a <a class="btn btn-action disabled" href="">{{text}}</a></li>',
            'counterRange' => '{{start}} - {{end}} of {{count}}',
            'counterPages' => '{{page}} of {{pages}}',
            'first' => '<li class="page-first"><a class="btn btn-action" href="{{url}}">{{text}}</a></li>',
            'last' => '<li class="page-last"><a class="btn btn-action" href="{{url}}">{{text}}</a></li>',
            'number' => '<a href="{{url}}"><span>{{text}}<span></a>',
            'current' => '<a  class="active"><span>{{text}}<span></a>',
            'ellipsis' => '<li class="ellipsis">...</li>',
            'sort' => '<a href="{{url}}">{{text}}</a>',
            'sortAsc' => '<a class="asc" href="{{url}}">{{text}}</a>',
            'sortDesc' => '<a class="desc" href="{{url}}">{{text}}</a>',
            'sortAscLocked' => '<a class="asc locked" href="{{url}}">{{text}}</a>',
            'sortDescLocked' => '<a class="desc locked" href="{{url}}">{{text}}</a>',
        ]
    ];    

    /**
     * PaginatorHelper::numbers()
     * 
     * @param mixed $options
     * @return void
     */
    public function numbers(array $options = [])
    {
          $xx = parent::numbers($options);
          
          $zxx = preg_replace_callback(
                '#\>(?:([\d+]))\<#',
                function ($matches) {
                    $matches[1] = p($matches[1]);
                    return ">{$matches[1]}<";
                },
                $xx
            );    

          
          return $zxx;
          debug($zxx);
          return '';          
    }
    
    public function PaginatorBar($model = null)
    {
        
    }
    
}