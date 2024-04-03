
let DataArray = [];
// var URL = 'https://olimpus.justcode.am/admin/';



$(document).ready(function() {
    $('.delete_photo').click(function() {
        var photoId = $(this).data('id');
        var photoUrl = $(this).attr('data_url');
        var confirmDelete = confirm('Вы действительно хотите удалить фотографию?');

        if (confirmDelete) {
            window.location.href = photoUrl;
        }
    });
});



$(document).ready(function () {
    $("#file").on('change keyup paste', function () {

        var numFiles = $('input[type="file"]')[0].files.length;
        let allUndefined = DataArray;
        let myArray = DataArray;
        let filteredArray = myArray.filter(item => item !== undefined);
        let allLenght = numFiles + filteredArray.length;

        $("#comment").attr("disabled", 'disabled');
        $("#comment").css("display", 'none');

        var file = $('input[type="file"]')[0].files.length;
        let time =  $.now();
        for (var i = 0; i < file; i++) {
            let type = $("input[type='file']")[0].files[i].type.split('/')[0]
            DataArray.push($("input[type='file']")[0].files[i]);

            if (type == 'image') {
                var fileUrl = URL.createObjectURL($("input[type='file']")[0].files[i]);
                $("#newDivqwe").append(`
                        <div class="PhotoDiv" style='overflow: visible;position: relative; width: 150px; height: 150px'>
                        <button  class="ixsButton" data-id="${DataArray.length-1}" style='
                                    outline: none;
                                    border: none;
                                position: relative;
                                background-color: transparent;
                                '></button>
                        <img class='sendPhoto' style='width: 150px; height: 150px' src='${fileUrl}'/>
                        </div>`);
            } else {
                $("#newDivqwe").append("  " +
                    "" +
                    "  <div class='PhotoDiv' style='overflow: visible;position: relative; width: 150px; height: 150px'>\n   " +
                    "                     <button class=\"ixsButton\" data-id="+`${DataArray.length-1}`+" style='\n                                position: relative;\n                                    outline: none;\n                                    border: none;\n                                position: relative;\n                                '></button>" +
                    "<i class=\"fileType fa fa-file fa-3x\" aria-hidden=\"true\"> </i></div>")
            }
        }
        $(".ixsButton").click(function (event) {
            event.preventDefault()
            let data_id = $(this).attr('data-id')
            $(this).parent('.PhotoDiv').hide()
            DataArray.splice(data_id,1,undefined)
            let data = DataArray;


            let allUndefined = true;
            $.each(data, function(index, item) {
                if (typeof item !== "undefined") {
                    allUndefined = false;
                    return false;
                }
            });
            if (allUndefined) {
                $("#comment").removeAttr("disabled", 'disabled');
                $("#comment").css("display", 'block');
            }
        })

    });
});

$('#create_product').on('submit',function(e) {
    e.preventDefault(); // Prevent the default form submission
    var formData = new FormData(this);
    let filteredArray = DataArray.filter(item => item !== undefined);
    let allLenght = filteredArray.length;


    let isAnyCheckboxChecked = $('.checkbox:checked').length > 0; // Check if any checkbox is checked




    if (isAnyCheckboxChecked) {
        if (allLenght >0 ){
            filteredArray.forEach(function(value, index) {
                formData.append('photo[]', value);
            });
            var token = $('meta[name="_token"]').attr('content');
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': token
                }
            });
            $('.submit_button').hide();
            $('.lds-ellipsis').show();
            $.ajax({
                url:  $(this).attr('action'),
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    console.log('ress--------------',response)
                    alert('Добавления успешно завершено');
                    window.location.replace(response.url);
                },
                error: function(xhr, status, error) {
                    $('.lds-ellipsis').hide();
                    $('.submit_button').show();
                    alert('Что то пошло не так свяжитесь с разработчиком')


                }
            });
        }else {
            alert('Выберите фотографию')
        }
    }else {
        alert('Выберите Категорию')
    }




});

$('#update_product').on('submit',function(e) {
    e.preventDefault(); // Prevent the default form submission
    var formData = new FormData(this);
    let filteredArray = DataArray.filter(item => item !== undefined);
    let allLenght = filteredArray.length;
    let isAnyCheckboxChecked = $('.checkbox:checked').length > 0; // Check if any checkbox is checked

        if (isAnyCheckboxChecked){
            filteredArray.forEach(function(value, index) {
                formData.append('photo[]', value);
            });
            var token = $('meta[name="_token"]').attr('content');
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': token
                }
            });
            $('.submit_button').hide();
            $('.lds-ellipsis').show();
            $.ajax({
                url:  $(this).attr('action'),
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    console.log('ress--------------',response)
                    alert('Редактирование успешно завершено');
                    window.location.replace(response.url);
                },
                error: function(xhr, status, error) {
                    $('.lds-ellipsis').hide();
                    $('.submit_button').show();
                    alert('Что то пошло не так свяжитесь с разработчиком')


                }
            });


        } else{
            alert('Выберите Категорию')
        }



});


$(document).ready(function() {
    let i = 0;
    $('#add-inputs').click(function() {
        i++;
        $('#input-container').append(
            `               <div class="form-group delete_inputs_div" bis_skin_checked="1" data_id="${i}" >
                                <div style="display: flex; justify-content: space-between">
                                <label >От</label>
                                <label >До</label>
                                <label >Цена   <span  style="color: red; cursor: pointer; " class="x_button_input">x</span></label>
                                </div>
                                <div style="display: flex; justify-content: space-between">
                                <input style="width: 30%" name="data[${i}][start_time]" type="time" class="form-control data" id="exampleInputName1" placeholder="От" required>
                                <input style="width: 30%" name="data[${i}][end_time]" type="time" class="form-control data" id="exampleInputName1" placeholder="До"  required>
                                <input style="-webkit-appearance: none; width: 30%" name="data[${i}][price]" type="number" class="form-control data" id="exampleInputName1" placeholder="Цена" required>
                            </div>
                            </div>`);

        $('.x_button_input').on('click', function () {

            $(this).closest('.delete_inputs_div').remove();
        });
    });

})

