<?php

/*
* Class HGridView
* Versão 1.0.0
* Autor: Eduardo Vieira
*/
namespace app\helpers;

class HGridView
{
    /*
    *  Função de contrução de tag;
    */
    private static function tag($tag,$text,$option=null)
    {
        
        $tags_especial = ['br','hr'];
        $item='';
        if(is_array($text))
        {
            foreach($text as $k=>$v)
            {
                $item .= $v;
            }
            $text = $item;
        }

        $input  = '<'.$tag.' ';
        ($option)?$input .= self::attr($option):false;
        $input .= ' >'.$text;
        (!in_array($tag,$tags_especial))? $input .= '</'.$tag.'>':false;

        return (String) $input;
    }
    /*
    * rows
    */
    public static function rows($option=null)
    {
        $dados = $option['dados'];
        $col   = array_filter($option['colums']);       
        $datakey = 0;
        $rows  ='';
        
        foreach($dados as $key =>$valor)
        {
            $rows .='<tr data-key="'.$datakey++.'">';
            $rows .= self::tag('td',$datakey);
            foreach($col as $k=>$v)
            {                
                if(is_array($v))
                {
                    if(!is_array($v['value'])){
                        (array_key_exists('option',$v))? $rows .= self::tag('td',$valor[$v['value']],$v['option']):$rows .= self::tag('td',$valor[$v['value']]);                        
                    }else{
                        (array_key_exists('option',$v))? $rows .= self::tag('td',$v['value'],$v['option']): $rows .= self::tag('td',$v['value']); ;
                    } 
                } else{
                        $rows .= self::tag('td',$valor[$v],[]);
                } 
            }
            $rows .='</tr>';
            
        }
        
        return $rows;
    }
    /*
    * table head
    */
    public static function tbHead($option=null)
    {
        $col = array_filter($option['colums']);
        
        $thead = self::tag('thead','{thead}');
        $tr    = self::tag('tr','{tr}'); 
        $th    = self::tag('th','#');

        foreach($col as $key=>$valor)
        {
            if(is_array($valor)){
                if(array_key_exists('option',$valor)){
                    $th .= self::tag('th',$valor['label'],$valor['option']);
                }else{
                    $th .= self::tag('th',$valor['label']);
                }
            }else{
                $th .= self::tag('th',$valor);
            } 
        }
        return (String) str_replace('{thead}',str_replace('{tr}',$th,$tr),$thead);
    }

    /*
    * widgets Grid
    */
    public static function widgets($option=null)
    {
        $dados = $option['dados'];
        $col   = array_filter($option['colums']);
        $optionTb = $option['option'];

        $table = self::tag('table','{head}{rows}',$optionTb); 

        $th = self::tbHead($option);
        
        $rows = self::rows($option);

        $table = str_replace('{head}',$th,$table);
        $table = str_replace('{rows}',$rows,$table);
        
        return $table;
    }
    
    private static function attr($option=null)
    {        
        $attr ='';
        foreach($option as $k =>$v)
        {
            $attr .= $k.'="'.$v.'"';
        }
        return $attr;
    }

    private static function format($input=null,$format=null)
    {
        switch($format){
            case 'moeda':
                $input =  number_format($input,2,",",".");
                break;
            case 'numero':
                $input =  number_format($input,2,",",".");
                break;
            default:
                $input = $input;
            break;
        }
        return $input;
    }

}

/*   
    HGridView::widgets(['dados'=>$model,
                      'option' =>['class'=>'table table-striped table-bordered',], 
                      'colums'=>[
                            'idusuarios',
                            'username',
                            'email',
                            'password',
                            ['atributo'=>'authKey',
                                'label'=>'Tipo Resp.',
                                'value'=>'authKey'
                            ],
                            ['atributo'=>'Col-link',
                                'label'=>'opções',
                                'option'=>['width'=>'300px',],
                                'value'=> [
                                            Html::a('Visualizar', Url::to(['usuarios/view']),[ 'title' => 'Visualizar', 'class' => 'gwCol btn btn-primary']),
                                            Html::a('Editar', Url::to(['usuarios/update']),[ 'title' => 'Editar', 'class' => 'gwCol btn btn-primary']),
                                            Html::a('Delete', Url::to(['usuarios/ajaxdelete']),[ 'title' => 'Delete', 'class' => 'gwCol btn btn-primary'])
                                          ],
                            ],
                       ],
                ])
*/