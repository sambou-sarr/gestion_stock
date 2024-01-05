<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage; 

class Tp2Controller extends Controller
{
    public function __construct()
    {
        if (Storage::disk('local')->exists('monfichier.txt')== false) {
            $text = '';
            Storage::disk('local')->put('monfichier.txt', $text); 
        }
        
    }

    public function index(){

        $header = 'liste des personnes';

        $text = Storage::disk('local')->get('monfichier.txt');
        $lines = explode(';',$text);
        $table = [];
        for ($i=0; $i < count( $lines) -1; $i++) { 
            array_push($table,explode(',',$lines[$i]));
        }
 
        return view('home',compact('table','header'));

    }

    public function store(Request $resquest){
        $prenom = $resquest->prenom;
        $nom = $resquest->nom;
        $datenaiss = $resquest->datenaiss;
        $tel = $resquest->tel;

        $today = new \DateTime();
        $dateconvert = new \DateTime($datenaiss);

        $diff = $today->diff($dateconvert);
        $age = $diff->y;
         $id = $this->getId();

        $personne = $id.','.$prenom.','.$nom.','.$datenaiss.','.$tel.','.$age.';';
        Storage::disk('local')->append('monfichier.txt',$personne);
        return redirect()->route('index');

    }


    public function getId(){

      $text =   Storage::disk('local')->get('monfichier.txt');

      $details = explode(';',$text);
      if (count($details) == 0 || count($details) == 1) {
          return 1;
      }else{
        

        $items = explode(',',$details[count($details)-2]);

        $id = (int)$items[0] + 1;

        return $id; 

      }
      
    }

    public function edite($id){

        $text =   Storage::disk('local')->get('monfichier.txt');
        $lines = explode(';',$text);
        for ($i=0; $i < count( $lines) -1; $i++) {
            $line =  $lines[$i];
            $items = explode(',',$line); 

            $idold = $items[0];
            //dump("id de la position actuel".$idold );
            if ($idold == $id ) {
               //dd("id trouber :".$id);
               return view('edit',compact('items'));
            }
        }

    }

    public function update(Request $resquest){

        
        $prenom = $resquest->prenom;
        $nom = $resquest->nom;
        $datenaiss = $resquest->datenaiss;
        $tel = $resquest->tel;

        $today = new \DateTime();
        $dateconvert = new \DateTime($datenaiss);

        $diff = $today->diff($dateconvert);
        $age = $diff->y;
         $id = $resquest->id;

        $personne = $id.','.$prenom.','.$nom.','.$datenaiss.','.$tel.','.$age;

        $text =   Storage::disk('local')->get('monfichier.txt');
        $lines = explode(';',$text);
        for ($i=0; $i < count( $lines) -1; $i++) {
            $line =  $lines[$i];
            $items = explode(',',$line); 

            $idold = $items[0];
            //dump("id de la position actuel".$idold );
            if ($idold == $id ) {

                $textfinal = str_replace($line, $personne,$text);

                $text =   Storage::disk('local')->put('monfichier.txt', $textfinal);
            
                return redirect()->route('index');
            }
        }

        // dump($text);
        // dump($personne);

    }

    public function supprimer($id){

        $text =   Storage::disk('local')->get('monfichier.txt');
        $lines = explode(';',$text);
        for ($i=0; $i < count( $lines) -1; $i++) {
            $line =  $lines[$i];
            $items = explode(',',$line); 

            $idold = $items[0];
            //dump("id de la position actuel".$idold );
            if ($idold == $id ) {
                // dd($line);
                $textfinal = str_replace($line.";","",$text);

                $text =   Storage::disk('local')->put('monfichier.txt', $textfinal);
            
                return redirect()->route('index');
            }

        }
    }

    public function max(){

        $header = 'personne la plus agé';
        $text =   Storage::disk('local')->get('monfichier.txt');
        $lines = explode(';',$text);

        $max = [];
        $agemax = 0;
        for ($i=0; $i < count( $lines) -1; $i++) {
            $line =  $lines[$i];
            $items = explode(',',$line); 
            $idold = $items[0];
            if($i==0){
                $max = $items;
                $agemax = (int)$items[5];
            }else{
                 

                if($agemax < (int)$items[5]){
                    $max = $items; 
                    $agemax = $items[5];
                }
            }

            

        }

        $table = $max;

            
            return view('maxmin',compact('table','header'));
            

    }

    public function min(){

        $header = 'personne la plus jeune';
        
        $text =   Storage::disk('local')->get('monfichier.txt');
        $lines = explode(';',$text);

        $max = [];
        $agemax = 0;
        for ($i=0; $i < count( $lines) -1; $i++) {
            $line =  $lines[$i];
            $items = explode(',',$line); 
            $idold = $items[0];
            if($i==0){
                $max = $items;
                $agemax = (int)$items[5];
            }else{

                if($agemax > (int)$items[5]){
                    $max = $items; 
                    $agemax = $items[5];
                }
            }

            

        }

        $table = $max;

            // return view('maxmin',compact('table'));
            return view('maxmin',compact('table','header'));
            
    }

}
