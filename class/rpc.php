<?php
/**
 * Class rpc
 *
 * @desc xmlrpc class.
 *
 * @note
 *
 */

require_once FORUM_PATH . 'etc/xmlrpc/Autoloader.php';
\PhpXmlRpc\Autoloader::register();
use PhpXmlRpc\Value;
use PhpXmlRpc\Request;
use PhpXmlRpc\Client;

class rpc {


    /**
     *
     * @param $api_endpoint
     * @param $api_id
     * @param $api_password
     * @return array
     */
    public function blogger_getUsersBlog($api_endpoint, $api_id, $api_password)
    {
        $ret = array();
        $client = new Client( $api_endpoint );
        $client->SetSSLVerifypeer(0);

        $request = new Request('blogger.getUsersBlogs',
            array(
                new Value( md5('key') , "string"),
                new Value( $api_id , "string"),
                new Value( $api_password , "string")
            )
        );
        $response = $client->send( $request );

        if ( $response->faultCode() ) {
            print "Fault <BR>";
            print "Code: " . htmlentities($response->faultCode()) . "<BR>" .
                "Reason: '" . htmlentities($response->faultString()) . "'<BR>";
        }
        else {
            echo 'Success<hr>';
            // di($response->val); Response 클래스의 바로 아래에 Value 클래스 객체가 들어가 있다.
            foreach ( $response->val as $valueArrays ) {
                foreach ( $valueArrays as $values ) {
                    foreach ( $values as $value ) {
                        di( $value );
                    }
                }
            }

            echo "<hr>";

            foreach ( $response->val as $val ) {
                $url = $val->me['struct']['url']->me['string'];
                $blogid = $val->me['struct']['blogid']->me['string'];
                $blogName = $val->me['struct']['blogName']->me['string'];
                $ret[] = [
                    'url' =>  $url,
                    'blogid' => $blogid,
                    'blogName' => $blogName
                ];
            }
        }

        return $ret;
    }
} // eo class


function rpc() {
    return new rpc();
}