<?php 


class IndexController extends BaseController
{
	//samo preusmjeri na dashboard
	public function index() {
        header( 'Location: ' . __SITE_URL . '/index.php?rt=index/dashboard' );
        exit();
	}

    //print dashboarda
    public function dashboard(){
        $sm = new movies_service();

        //dohvati 25 najnovijh filmova
        $movies = $sm->get_movies();
        //spremi u registry variablu da ih view može ispisati
        $this->registry->template->movies = $movies;

        $this->registry->template->title = 'Dashboard!';
        $this->registry->template->show( 'view_dashboard' );
    }

    //dodavanje novog komentara
    public function add_new_comment(){
        $sm = new movies_service();

        if( isset($_POST['button']) && substr($_POST['button'],0, 8) === 'comment_' ){
            $movie = substr($_POST['button'], 8);//npr comment_1565 -> vrati nam 1565
            $name = 'new_comment_' . $movie;

            if( isset($_POST[$name]) &&  strlen($_POST[$name]) !== 0 ){
                $comm = $_POST[$name];
                $sm->add_comment($comm, $movie);
            }
        }

        //i u else slučaju i u if slučaju ispisujemo top 25 filmova
        header( 'Location: ' . __SITE_URL . '/index.php?rt=index/dashboard' );
        exit();
    }

    //dohvat statistike - map/reduce
    public function statistics(){
        $sm = new movies_service();

        if( isset($_POST['button']) && $_POST['button'] === 'a' ){
            $a = $sm->stat_a();
            $this->registry->template->a = $a;
            $this->registry->template->title = 'Map/reduce';
            $this->registry->template->show( 'view_statistics' );
        }
        else if( isset($_POST['button']) && $_POST['button'] === 'b' ){
            $b = $sm->stat_b();
            $this->registry->template->b = $b;
            $this->registry->template->title = 'Map/reduce';
            $this->registry->template->show( 'view_statistics' );
        }
        else if( isset($_POST['button']) && $_POST['button'] === 'c' ){
            $c = $sm->stat_c();
            $this->registry->template->c = $c;
            $this->registry->template->title = 'Map/reduce';
            $this->registry->template->show( 'view_statistics' );
        }
    }



}; 

?>