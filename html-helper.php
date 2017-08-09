<?php
/*
Author: Cem Yıldız
Author URI: http://mingus.co
*/
class MingusHtml
{
    private function getParams($params=NULL)
    {
        $r = '';
        if(is_array($params)):
            foreach ($params as $key => $value):
                $r .= ' '.$key.'="'.$value.'"';
            endforeach;
        endif;
        return $r;
    }
    public function tableWithTitles($titles,$data,$params=NULL,$paramsTr=NULL,$paramsTd=NULL)
    {
        $r = '<table '.$this->getParams($params).'>';
        $content = '';
        foreach ($titles as $td) {
            $content .= $this->gen('td',$td,$paramsTd);
        }
        $thead = $this->gen('thead',$this->gen('tr',$content,$paramsTr));
        $content = '';
        foreach ($data as $tr) {
            $c_ = '';
            foreach ($tr as $td) {
                $c_ .= $this->gen('td',$td,$paramsTd);
            }
            $content .= $this->gen('tr',$c_,$paramsTr);
        }
        $tbody = $this->gen('tbody',$content);
        $r .= $thead.$tbody.'</table>';
        return $r;
    }
    public function table($data,$params=NULL,$paramsTr=NULL,$paramsTd=NULL)
    {
        $r = '<table '.$this->getParams($params).'>';
        $r2 = '';
        $keys = array();
        foreach ($data as $tr) {
            $content = '';
            foreach ($tr as $key => $td) {
                if(!in_array($key, $keys)){
                    array_push($keys, $key);
                }
                $content .= $this->gen('td',$td,$paramsTd);
            }
            $r2 .= $this->gen('tr',$content,$paramsTr);
        }
        $content = '';
        foreach ($keys as $key) {
            $content .= $this->gen('td',$key,$paramsTr);
        }
        $r .= $this->gen('thead',$this->gen('tr',$content,$paramsTr)).$r2.'</table>';
        return $r;
    }
    public function gen($tag,$content,$params=NULL)
    {
        $r = '<'.$tag.$this->getParams($params).'>';
        $r .= $content;
        $r .= '</'.$tag.'>';
        return $r;
    }
    //titles h1-h6
    public function h($size,$label,$params=NULL)
    {
        $r = '<h'.$size.$this->getParams($params).'>';
        $r .= $label;
        $r .= '</h'.$size.'>';
        return $r;
    }
    //href
    public function a($href,$label=NULL,$params=NULL)
    {
        if($label==NULL):
            $label = $href;
        endif;
        $r = '<a href="'.$href.'"'.$this->getParams($params).'>';
        $r .= $label;
        $r .= '</a>';
        return $r;
    }
    //form elements
    public function form($content, $action='',$method='post',$params=NULL){
        $r = '<form action="'.$action.'" method="'.$method.'"'.$this->getParams($params).'>';
        $r .= $content;
        $r .= '</form>';
        return $r;
    }
    //select
    public function select($data,$params=NULL,$selected=NULL)
    {
        $r = '<select'.$this->getParams($params).'>';
        foreach($data as $key=>$value):
            $s = '';
            if($selected!=NULL && $selected==$key):
                $s = ' selected="selected"';
            endif;
            $r .= '<option value="'.$key.'"'.$s.'>'.$value.'</option>';
        endforeach;
        $r .= '</select>';
        return $r;
    }
    //input
    public function input($name, $type="text", $value="",$params=NULL)
    {
        $r = '<input type="'.$type.'" name="'.$name.'" value="'.$value.'"'.$this->getParams($params).' />';
        return $r;
    }
    //button
    public function button($label,$params=NULL)
    {
        $r = '<button'.$this->getParams($params).'>';
        $r .= $label;
        $r .= '</button>';
        return $r;
    }
    //datalist option
    public function datalist($values,$params=NULL)
    {
        $r = '<datalist id="'.$this->getParams($params).'">';
        foreach($values as $value):
            $r .= '<option value="'.$value.'">';
        endforeach;
        $r .= '</datalist>';
        return $r;
    }
    //listing
    public function li($content,$params=NULL)
    {
        $r = '<li'.$this->getParams($params).'>';
        $r .= $content;
        $r .= '</li>';
        return $r;
    }
    //paragraph
    public function p($content,$params=NULL)
    {
        $r = '<p'.$this->getParams($params).'>';
        $r .= $content;
        $r .= '</p>';
        return $r;
    }
    //division
    public function div($content,$params=NULL)
    {
        $r = '<div'.$this->getParams($params).'>';
        $r .= $content;
        $r .= '</div>';
        return $r;
    }
    //span
    public function span($content,$params=NULL)
    {
        $r = '<span'.$this->getParams($params).'>';
        $r .= $content;
        $r .= '</span>';
        return $r;
    }
    //strong
    public function strong($content,$params=NULL)
    {
        $r = '<strong'.$this->getParams($params).'>';
        $r .= $content;
        $r .= '</strong>';
        return $r;
    }
    //italic
    public function i($content,$params=NULL)
    {
        $r = '<i'.$this->getParams($params).'>';
        $r .= $content;
        $r .= '</i>';
        return $r;
    }
    //img
    public function img($src,$params=NULL)
    {
        $r = '<img src="'.$src.'"'.$this->getParams($params).'>';
        return $r;
    }
    public function br()
    {
        return '<br />';
    }
    public function hr()
    {
        return '<hr />';
    }
}