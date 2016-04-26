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
	klog('blogger_getUsersBlogs');
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
            $this->error($response);
        }
        else {
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

    /**
     * @param $response
     */
    private function error( $response )
    {
        $msg = "Fault\n";
        $msg .= "Code: " . $response->faultCode() . "\n";
        $msg .= "Reason: '" . $response->faultString() . "\n";
        klog($msg);
    }

    public function metaWeblog_getCategories($api_endpoint, $api_id, $api_password) {
	klog('metaWeblog_getCategories');
        $ret = array();
        $client = new Client( $api_endpoint );
        $client->SetSSLVerifypeer(0);
        $request = new Request( 'metaWeblog.getCategories',
            array(
                new Value( md5('key') , "string"),
                new Value( $api_id , "string"),
                new Value( $api_password , "string")
            )
        );
        $response = $client->send( $request );
        if ( $response->faultCode() ) $this->error($response);
        else {
            foreach ( $response->val as $val ) {
                $r = [];
                if ( isset( $val->me['struct']['description'] ) ) {
                    $r['description'] = $val->me['struct']['description']->me['string'];
                }
                if ( isset( $val->me['struct']['title'] ) ) {
                    $r['title'] = $val->me['struct']['title']->me['string'];
                }
                $ret[] = $r;
            }
        }
        return $ret;
    }

    /**
     * @param $api_blogid
     * @param $api_endpoint
     * @param $api_id
     * @param $api_password
     * @param $post
     * @param bool|true $publish
     * @return int
     */
    public function metaWeblog_newPost($api_endpoint, $api_id, $api_password, $api_blogid, $post, $publish = true)
    {

	klog("metaWeblog_newPost: $api_endpoint, $api_id, $api_blogid");
	klog($post);
        $postID = 0;
        $struct = array(
            'title' => new Value($post['title'], "string"),
            'description' => new Value($post['description'], "string")
        );

        $client = new Client( $api_endpoint );
        $client->SetSSLVerifypeer(0);
        $request = new Request( 'metaWeblog.newPost',
            array(
                new Value( $api_blogid , "string"),
                new Value( $api_id , "string"),
                new Value( $api_password , "string"),
                new Value( $struct , "struct"),
                new Value( $publish , "boolean"),
            )
        );

        $response = $client->send( $request );
        if ( $response->faultCode() ) {
            $this->error($response);
        }
        else {
		klog('response OK');
            $postID = $response->val->me['string'];
        }

        return $postID;
    }

    public function metaWeblog_editPost($api_endpoint, $api_id, $api_password, $api_blogid, $post, $publish = true)
    {
        $boolean = false;
        $struct = array(
            'title' => new Value($post['title'], "string"),
            'description' => new Value($post['description'], "string")
        );
        $client = new Client( $api_endpoint );
        $client->SetSSLVerifypeer(0);
        $request = new Request( 'metaWeblog.editPost',
            array(
                new Value( $api_blogid , "string"),
                new Value( $api_id , "string"),
                new Value( $api_password , "string"),
                new Value( $struct , "struct"),
                new Value( $publish , "boolean"),
            )
        );

        $response = $client->send( $request );

        if ( $response->faultCode() ) {
            $this->error($response);
        }
        else {
            $boolean = $response->val->me['boolean'];
        }

        return $boolean;
    }

    /**
     * @param $api_endpoint
     * @param $api_id
     * @param $api_password
     * @param $postID
     * @return bool
     */
    public function blogger_deletePost($api_endpoint, $api_id, $api_password, $postID)
    {

        $boolean = false;
        $client = new Client( $api_endpoint );
        $client->SetSSLVerifypeer(0);
        $request = new Request( 'blogger.deletePost',
            array(
                new Value( md5('key'), "string"),
                new Value( $postID , "string"),
                new Value( $api_id , "string"),
                new Value( $api_password , "string"),
                new Value( 0 , "boolean"),
            )
        );

        $response = $client->send( $request );

        if ( $response->faultCode() ) {
            $this->error($response);
        }
        else {
            $boolean = $response->val->me['boolean'];
        }

        return $boolean;
    }

} // eo class


function rpc() {
    return new rpc();
}
