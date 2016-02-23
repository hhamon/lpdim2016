<?php
/**
 * Created by PhpStorm.
 * User: Julien
 * Date: 17/02/2016
 * Time: 21:51
 */

namespace Application\Html;


class HtmlBuilder
{
    public function __construct()
    {

    }

    /**
     * @param $label
     * @param $type
     * @param $name
     * @param string $value
     * @return string
     */
    public function inputLabel($label, $type, $name, $value = '')
    {
        $string = "<div class=\"form-group\">";
        $string .= $this->label($label,$name);
        $string .= $this->input($type,$name,$value);
        $string .= "</div>";
        return $string;
    }

    /**
     * @param $label
     * @param $name
     * @param string $value
     * @return string
     */
    public function textareaLabel($label, $name, $value = '')
    {
        $string = "<div class=\"form-group\">";
        $string .= $this->label($label,$name);
        $string .= $this->textarea($name,$value);
        $string .= "</div>";
        return $string;
    }

    /**
     * @param $label
     * @param $for
     * @return string
     */
    public function label($label, $for)
    {
        return "<label for=\"{$for}\" class=\"control-label\">{$label}</label>";
    }

    /**
     * @param $type
     * @param $name
     * @param string $value
     * @return string
     */
    public function input($type, $name, $value = '')
    {
        return "<input type=\"{$type}\" name=\"{$name}\" id=\"{$name}\" value=\"{$value}\" class=\"form-control\"/>";
    }

    /**
     * @param $name
     * @param string $value
     * @return string
     */
    public function textarea($name, $value = '')
    {
        return "<textarea name=\"{$name}\" id=\"{$name}\" class=\"form-control\">{$value}</textarea>";
    }

    /**
     * @param $method
     * @param string $action
     * @return string
     */
    public function form($method, $action = '')
    {
        return "<form action=\"{$action}\" method=\"{$method}\" class=\"form\">";
    }

    /**
     * @param string $submit
     * @return string
     */
    public function form_end($submit = '')
    {
        if(!$submit){
            return "</form>";
        }
        return "<button type=\"submit\" class=\"btn btn-primary\">{$submit}</button>
</form>";
    }
}