import { Controller } from '@hotwired/stimulus';


export default class extends Controller {


    helloworld(){
        alert("HELLO WORLD")
        console.log("HELLO WORLD YHEA")
    }

    delete(){
        if(confirm("Voulez vous vraiment supprimer cet element ? ")){
            alert("Done")
        }
    }

    // ...
}