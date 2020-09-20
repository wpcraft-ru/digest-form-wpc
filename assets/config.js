(function($) {
	
    $(document).ready(function() {

        // console.log(wpApiSettings);
        $('.select-products-input').select2({
            ajax: {
              url: wpApiSettings.root + 'wp/v2/tags',
              dataType: 'json',
              data: function (params) {
                var query = {
                  search: params.term,
                  page: params.page || 1
                }
          
                // Query parameters will be ?search=[term]&page=[page]
                return query;
              },
              processResults: function (data) {

                console.log(data);
                // Transforms the top-level key of the response object from 'items' to 'results'
                return {
                    results: jQuery.map( data, function( obj ) {
                        return {
                            id: obj.id,
                            text: obj.name
                        };
                    } )
                }
              }
              // Additional AJAX parameters go here; see the end of this chapter for the full code of this example
            }
          });
    });
        
})( jQuery );

//choices config for select-products-input
addEventListener("DOMContentLoaded", function () {

    // var element = document.querySelector('.select-products-input');
    // var choices = new Choices(element, {
    //     removeItemButton: false

    // });


    // choices.setChoices(
    //     [
    //         { value: 'One', label: 'Label One', disabled: true },
    //         { value: 'Two', label: 'Label Two', selected: true },
    //         { value: 'Three', label: 'Label Three' },
    //     ],
    //     'value',
    //     'label',
    //     false,
    // );

    // Passing a function that returns Promise of choices
    // choices.setChoices(function (e) {

    //     console.log(e);

    //     return fetch(
    //         'https://api.discogs.com/artists/55980/releases?token=QBRmstCkwXEvCjTclCpumbtNwvVkEzGAdELXyRyW'
    //     )
    //         .then(function (response) {
    //             return response.json();
    //         })
    //         .then(function (data) {
    //             return data.releases.map(function (release) {
    //                 return { value: release.title, label: release.title };
    //             });
    //         });
    // });

    // setChoices(function () {

    // });

});

//additional checkbox - local storage
addEventListener("DOMContentLoaded", function () {

    var checkbox = document.getElementById("additional-enable");

    var checked = JSON.parse(localStorage.getItem('additional-enable'));
    if (checked == true) {
        checkbox.checked = true;
    } else {
        checkbox.checked = false;
    }

    checkbox.addEventListener('change', function (e) {

        if (e.target.checked == true) {
            localStorage.setItem('additional-enable', true);
        } else {
            localStorage.setItem('additional-enable', false);
        }

    });


});
