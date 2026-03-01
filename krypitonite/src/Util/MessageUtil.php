<?php
namespace Krypitonite\Util;

class MessageUtil
{

    /**
     *
     * @param String $message            
     */
    public static function addMessageSucces($message)
    {
        $div = "<div id='modal-global' class='modal fade bd-example-modal-sm' tabindex='-1' role='dialog' aria-labelledby='mySmallModalLabel' aria-hidden='true'>
                  <div class='modal-dialog modal-sm'>
                    <div class='modal-conten'>
                        <div style='width: 400px; 
                                    height:60px; 
                                    border-radius:5px; 
                                    text-align: center; 
                                    background-color: #696969;'>
                            <h5 style='color: #FFF; margin-top: 100px;'><br>" . $message . "</div></h5></div>
                  </div>
                </div>";
        
        echo $div;
    }

    /**
     *
     * @param String $message            
     */
    public static function addMessageError($message)
    {
        $div = "<div id='modal-global' class='modal fade bd-example-modal-sm' tabindex='-1' role='dialog' aria-labelledby='mySmallModalLabel' aria-hidden='true'>
                  <div class='modal-dialog modal-sm'>
                    <div class='modal-conten'>
                        <div style='width: 400px;
                                    height:60px;
                                    border-radius:5px;
                                    text-align: center;
                                    background-color: #2E8B57;
                                    background: rgba(255, 0, 54, 0.2);'>
                            <h5 style='color: #FFF; margin-top: 100px;'><br>" . $message . "</div></h5></div>
                  </div>
                </div>";
        
        echo $div;
    }
}