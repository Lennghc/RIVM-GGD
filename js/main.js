$(document).ready(function(){
    //Wait till document has been made
    grabFromModal("coronaModal");
    grabFromModal("rivmModal");
    grabFromModal("ggdModal");
});

function grabFromModal(id){
    var h5 = $("#" + id).find( "div.modal-body" ).find("h5");
    var p  =  $("#" + id).find( "div.modal-body" ).find("p");
    
    //We gather the information from the modal with this id.
    //Use the find function so whe can check how many html h5 tags this parent element has.
    //Make a for loop to loop through them all and add them to the carousel for sweet displaying

    for(let i = 0; i < p.length; i++)
    {
        if(h5[i]) {
            var current_h5 = $( h5[i] );
        } else {
            current_h5 = $("#" + id).find( "h5.modal-title" );
        }

        var current_p = $( p[i] );
        
        $('<div class="carousel-item text-center p-4"><h5 class="pb-2 text-light">'+ current_h5.text() +'</h5><p class="mx-5 px-5">'+ truncateWithEllipses(current_p.text(), 150) +'</p> </div>').appendTo('.carousel-inner');
    }

    //Here we add the data to our carousel
    //and display it

    $('.carousel-item').first().addClass('active');
    $('#carousel').carousel();
}

//Simple text cutoff function with added ellipses

function truncateWithEllipses(text, max) 
{
    return text.substr(0,max-1)+(text.length>max?'&hellip;':''); 
}