<?php
    function alertMsg($message, $title = null, $alert = 'info', $button = true, $auto_delete = false)
    {
        $result = "";

        switch ($alert) {
            case 'success':
                $alert = "alert-success";
                break;

            case 'danger':
                $alert = "alert-danger";
                break;
            
            case 'warning':
                $alert = "alert-warning";
                break;
            
            case 'info':
            default:
                $alert = "alert-info";
                break;
        }

        $result .= '<div class="alert ' . $alert . ' alert-dismissible fade show py-4 mb-5" role="alert">';

        if ($title != null) {
            $result .= '<h5 class="alert-heading">' . $title . '</h5>';
        }

        if ( $button ) {
            $result .= '<button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>';
        }

        $result .= $message;

        $result .= '</div>';

        return $result;
        
    }
?>