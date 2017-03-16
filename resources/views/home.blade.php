@extends('layouts.app')

@section('content')
    <script>(function(d, s, id) {
            var js, fjs = d.getElementsByTagName(s)[0];
            if (d.getElementById(id)) return;
            js = d.createElement(s); js.id = id;
            js.src = "//connect.facebook.net/pl_PL/sdk.js#xfbml=1&version=v2.8&appId=1731780663779351";
            fjs.parentNode.insertBefore(js, fjs);
        }(document, 'script', 'facebook-jssdk'));
    </script>

    <div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="row" style="border-bottom:1px solid #CCC;padding-bottom:20px;">
                        <?php
                        $token = Session::get('fb_user_access_token');
                        $fb = new Facebook\Facebook([
                            'app_id' => 'your-appId',
                            'app_secret' => 'your-appSecret',
                            'default_graph_version' => 'v2.2',
                        ]);
                        /*------------------------------------------------------------------*/
                        try {
                            $response = $fb->get('me?fields=albums{count,photos{images.limit(4){source}}}', $token);
                        } catch(Facebook\Exceptions\FacebookResponseException $e) {
                            echo 'Graph returned an error: ' . $e->getMessage();
                            exit;
                        } catch(Facebook\Exceptions\FacebookSDKException $e) {
                            echo 'Facebook SDK returned an error: ' . $e->getMessage();
                            exit;
                        }

                        $user = $response->getGraphObject();
                        $images = json_decode($user);
                        for ($i=0; $i < $images->albums[0]->count ; $i++) {
                            foreach ($images->albums[0]->photos[$i]->images[1] as $key) {
                                echo "<div  class='gallery_product col-lg-3 col-md-3 col-sm-3col-xs-5 filter hdpe'><img src=".$key." class='img-responsive'></div>";
                            }
                        }
                        /*-----------------------------------------------------------------*/
                        ?>

                    </div><div class="row" style="margin-top:20px;">

                        <?php
                        /*----------------------------------------------------------------*/
                        try {
                            // Returns a `Facebook\FacebookResponse` object
                            $res_posts = $fb->get('me/?fields=id,posts.limit(3){id}', $token);
                        } catch(Facebook\Exceptions\FacebookResponseException $e) {
                            echo 'Graph returned an error: ' . $e->getMessage();
                            exit;
                        } catch(Facebook\Exceptions\FacebookSDKException $e) {
                            echo 'Facebook SDK returned an error: ' . $e->getMessage();
                            exit;
                        }
                        $posts_obj = $res_posts->getGraphObject();
                        $posts = json_decode($posts_obj);
                        $user_id=$posts->id;
                        foreach ($posts->posts as $post) {
                            $link = explode('_',$post->id);
                            // var_dump($link);
                            echo "<div class='fb-post' style='margin-bottom:20px; margin-left: 25%' data-href='https://www.facebook.com/".$user_id."/posts/".$link[1]."/' data-width='500' data-show-text='true'><blockquote cite='https://www.facebook.com/".$user_id."/posts/".$link[1]."/' class='fb-xfbml-parse-ignore'>Opublikowany przez <a href='https://www.facebook.com/facebook/'>Facebook</a> na&nbsp;<a href='https://www.facebook.com/".$user_id."/posts/".$link[1]."/'>27 sierpnia 2015</a></blockquote></div>";

                        }

                        /*-------------------------------------------------------------------*/
                        ?>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
