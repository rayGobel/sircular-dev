<?php namespace App\Debug;

class VarDebug {

    public function __construct($data)
    {
        $this->displayFields($data);

    }

    private function displayFields($data, $html='')
    {
        $html .= '<hr></hr>';
        if (is_object($data) || is_array($data)) {

            foreach($data as $key=>$val) {
                if (is_object($val) || is_array($val)) {
                    $html .= $this->displayFields($val);
                } else {
                    $html .= "<div class='row'>
                        <div class='col-xs-3'>{$key}</div>
                        <div class='col-xs-3'>{$val}</div>
                        </div>";
                }
            }
        } else {
            $html .= "<div class='row'>
                <div class='col-xs-3'>{$data}</div>
                </div>";
        }
        echo $html;
    }
                        
}

