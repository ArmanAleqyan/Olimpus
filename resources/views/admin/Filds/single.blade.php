@extends('admin.layouts.default')
@section('title')
    Поля
@endsection

<style>
    input{
        color: white !important;
    }
    input::-webkit-outer-spin-button,
    input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }
</style>


@section('content')

    @if(session('created'))
        <script>
            Swal.fire({
                position: 'center',
                icon: 'success',
                title: 'Your work has been saved',
                showConfirmButton: false,
                timer: 3000
            })
        </script>
    @endif

    <div class="col-12 grid-margin stretch-card" bis_skin_checked="1">
        <div class="card" bis_skin_checked="1">
            <div class="card-body" bis_skin_checked="1">
                <h4 class="card-title">Редактирование  поля</h4>
                <form  id="update_product" action="{{route('update_fild')}}" method="post" class="forms-sample">
                    @csrf
                    <br>
                    <div style="display: flex; flex-wrap: wrap; gap: 20px">
                        @foreach($sports as $item)
                            <div class="form-check" bis_skin_checked="1" style="width: 10%">
                                <label class="form-check-label" style="color: white !important;">
                                        <?php $get_sport_type = \App\Models\FildSportType::where('fild_id', $get->id)->where('sport_id', $item->id)->first() ?>
                                    @if($get_sport_type != null)
                                    <input checked style="cursor: pointer;" type="checkbox" class="form-check-input checkbox" value="{{$item->id}}" name="sport_type[]" > {{$item->name}} <i class="input-helper"></i></label>
                                    @else
                                    <input  style="cursor: pointer;" type="checkbox" class="form-check-input checkbox" value="{{$item->id}}" name="sport_type[]" > {{$item->name}} <i class="input-helper"></i></label>
                                    @endif
                            </div>
                        @endforeach
                    </div>
                    <br>
                    <br>
                    <input type="hidden" name="fild_id" value="{{$get->id}}">
                    <div class="form-group" bis_skin_checked="1">
                        <label for="exampleFormControlSelect2">Город</label>
                        <select name="city_id" style="color: white" class="form-control" id="exampleFormControlSelect2">
                            @foreach($city as $item)
                                @if($get->city_id == $item->id)
                                <option value="{{$item->id}}" selected>{{$item->name}}</option>
                                @else
                                    <option value="{{$item->id}}">{{$item->name}}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group" bis_skin_checked="1">
                        <label for="exampleInputName1">Название</label>
                        <input type="text" class="form-control" id="exampleInputName1" placeholder="Название" name="name" value="{{$get->name}}">
                    </div>

                    <div class="form-group" bis_skin_checked="1">
                        <label for="exampleInputName1">Долгота</label>
                        <input type="text" class="form-control" id="exampleInputName1" placeholder="Долгота" name="latitude" value="{{$get->latitude}}">
                    </div>


                    <div class="form-group" bis_skin_checked="1">
                        <label for="exampleInputName1">Широта</label>
                        <input type="text" class="form-control" id="exampleInputName1" placeholder="Широта" name="longitude" value="{{$get->longitude}}">
                    </div>

                    <div class="form-group" bis_skin_checked="1">
                        <label for="exampleInputName1">Адрес</label>
                        <input type="text" class="form-control" id="exampleInputName1" placeholder="Адрес" name="address" value="{{$get->address}}">
                    </div>

                    <div class="form-group" bis_skin_checked="1">
                        <label for="exampleInputName1">Вместимость Пользвателей</label>
                        <input type="text" class="form-control" id="exampleInputName1" placeholder="Вместимость Пользвателей" name="users_count" value="{{$get->users_count}}" >
                    </div>
                    <div class="form-group" bis_skin_checked="1">
                        <label for="exampleInputName1">Особенности</label>
                        <input type="text" class="form-control" id="exampleInputName1" placeholder="Особенности" name="peculiarities" value="{{$get->peculiarities}}">
                    </div>
                    <div class="form-group" bis_skin_checked="1">
                        <label for="exampleInputName1">Описание</label>
                        <input type="text" class="form-control" id="exampleInputName1" placeholder="Описание" name="description" value="{{$get->description}}">
                    </div>
                    <div class="form-group" bis_skin_checked="1">
                        <label for="exampleInputName1">Размер</label>
                        <input type="text" class="form-control" id="exampleInputName1" placeholder="Размер" name="size" value="{{$get->size}}">
                    </div>
                    <div class="form-group" bis_skin_checked="1">
                        <label for="exampleInputName1">Покрытие</label>
                        <input type="text" class="form-control" id="exampleInputName1" placeholder="Покрытие" name="covering" value="{{$get->covering}}">
                    </div>

                    <br>
                    <br>
                    <h3>График работы</h3>
                    <br>
                    <div id="input-container" bis_skin_checked="1">
                        @foreach($get->grafik as $grafik)
                        <div class="form-group " bis_skin_checked="1" data_id="" >
                            <div style="display: flex; justify-content: space-between">
                                <label >От</label>
                                <label >До</label>
                                <label >Цена <a href="{{route('delete_fild_grafik', $grafik->id)}}"><span  style="color: red; cursor: pointer; " class="">x</span></a> </label>
                            </div>
                            <div style="display: flex; justify-content: space-between">
                                <input style="width: 30%" name="old_data[{{$grafik->id}}][start_time]" type="time" value="{{$grafik->start}}" class="form-control data" id="exampleInputName1" placeholder="От" required>
                                <input style="width: 30%" name="old_data[{{$grafik->id}}][end_time]" type="time" value="{{$grafik->end}}" class="form-control data" id="exampleInputName1" placeholder="До"  required>
                                <input style="-webkit-appearance: none; width: 30%" name="old_data[{{$grafik->id}}][price]" value="{{$grafik->price}}" type="number" class="form-control data" id="exampleInputName1" placeholder="Цена" required>
                            </div>
                        </div>
                            @endforeach
                    </div>
                    <button type="button" class="btn btn-inverse-light btn-fw" id="add-inputs">Добавить ещё</button>
                    <br><br>
                    <div class="form-group" bis_skin_checked="1">
                        <label class="btn btn-outline-warning" for="file">Выберете фотографии</label>
                        <input style="display: none"  type="file"  id="file" accept="image/*" multiple  >
                        <div id="imagePreview">
                            <div id="newDivqwe">

                                @foreach($get->photo as $photo)
                                <div class="PhotoDiv" style='overflow: visible;position: relative; width: 150px; height: 150px'>
                                    @if($get->photo->count() > 1)
                                    <button type="button"  class="ixsButton delete_photo" data_url="{{route('delete_photo', $photo->id)}}" data-id="{{$photo->id}}" style='
                                    outline: none;
                                    border: none;
                                position: relative;
                                background-color: transparent;
                                '></button>
                                    @endif
                                    <img class='sendPhoto' style='width: 150px; height: 150px' src='{{asset('uploads/'.$photo->photo)}}'/>
                                </div>
                                    @endforeach

                            </div>
                        </div>
                    </div>
                    <br>
                    <br>
                    <br>
                    <br>
                        <div style="display: flex; justify-content: space-between">

                            <button type="submit" class="btn btn-inverse-success btn-fw">Сохранить  изменения</button>
                            <a href="{{route('delete_fild', $get->id)}}"  class="btn btn-inverse-danger btn-fw">Удалить</a>
                        </div>
                </form>
            </div>
        </div>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>

    <script src="{{asset('admin//js/product.js')}}"></script>
@endsection

