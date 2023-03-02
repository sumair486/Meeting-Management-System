

@extends('layout')

@section('main-section')



<div class="container">
    <a href="{{ url('logout') }}">Logout</a>
    <h1>Meeting Management System</h1>
    @if(Session::has('status'))
    <div class="alert alert-success">
        {{session('status')}}
    </div>
@endif

@if(Session::has('error'))
<div class="alert alert-danger">
    {{session('error')}}
</div>
@endif

@if(Session::has('error1'))
<div class="alert alert-danger">
    {{session('error')}}
</div>
@endif

    <form  action="{{ url('home') }}" method="post">

      @csrf
        <!-- 2 column grid layout with text inputs for the first and last names -->
        <div class="row mb-4">
          <div class="col">
            <div class="form-outline">
              <input type="hidden" name="user_id" value="{{ Auth::id() }}"/>
              <label class="form-label" for="form3Example1">Location</label>

              <input type="text" name="location" id="location" class="form-control" />
            </div>
          </div>
          <div class="col">
            <div class="form-outline">
              <label class="form-label"  for="form3Example2">Client Name</label>

              <input type="text" name="name" placeholder="Enter Name" class="form-control" />
            </div>
          </div>
          <div class="col">
            <div class="form-outline">
              <label class="form-label" for="form3Example2">Meeting Time</label>

              <input type="number" name="meeting_time" placeholder="Meeting Time (in Minutes)" class="form-control" />
              <span>(9AM to 6 PM)</span>

              
            </div>
          </div>
        </div>

        <div class="row mb-4">
            <div class="col">
              <div class="form-outline">
                <label class="form-label" for="form3Example1">Date</label>

                <input type="date" name="date" id="date" placeholder="Date" class="form-control" />
              </div>
            </div>
            <div class="col">
              <div class="form-outline">
                <label class="form-label" for="form3Example2">Latitude</label>

                <input type="text" placeholder="latitude" name="latitude" id="latitude" class="form-control" />
              </div>
            </div>
            <div class="col">
              <div class="form-outline">
                <label class="form-label" for="form3Example2">Longitude</label>

                <input type="text"  placeholder="Longitude" name="longitude" id="longitude" class="form-control" />
              </div>
            </div>
          </div>

          <div class="row mb-4">
            <div class="col">
              <div class="form-outline">
                <label class="form-label" for="form3Example1">IP Address</label>

                <input type="text" placeholder="ip address" name="ip" id="ip" class="form-control" />
              </div>
            </div>
            <div class="col">
              <div class="form-outline">
                <label class="form-label" for="form3Example2">City</label>

                <input type="text" placeholder="city" name="city" id="city" class="form-control" />
              </div>
            </div>
            <div class="col">
              <div class="form-outline">
                <label class="form-label" for="form3Example2">Distance Time</label>

                <input type="text" name="dtime" id="dtime" placeholder="Time in Minutes" class="form-control" />
              </div>
            </div>
          </div>

          <div class="row mb-4">
            <div class="col">
              <div class="form-outline">
                <label class="form-label" for="form3Example1">Kilometer</label>

                <input type="text" name="dkm" id="dkm" placeholder="Kilometer" class="form-control" />
              </div>
            </div>


          </div>


      
        <!-- Email input -->

      
        <!-- Password input -->

      
        <!-- Checkbox -->

      
        <!-- Submit button -->
        <button type="submit" class="btn btn-primary btn-block mb-4">Sign up</button>
      

      </form>


</div>

{{-- Show data --}}


<div class="container-fluid">
  <div class="row">
    <div class="col-md-12">
      <div class="table-responsive">
      <table class="table table-striped table-dark">
        <thead>
          <tr>
            <th scope="col">S.No</th>
            <th scope="col">Name</th>
            <th scope="col">Location</th>
            <th scope="col">Latitude</th>
            <th scope="col">Longitude</th>
            <th scope="col">Meeting time</th>
            <th scope="col">Distance Time</th>
            <th scope="col">Distance KM</th>
            <th scope="col">Date</th>

          </tr>
        </thead>
        <tbody class="tbody">
          @if (count($meetings) > 0)
            
          @foreach ($meetings as $key=>$meeting)
            
          <tr>
            <th>{{ $key+1 }}</th>
            <td>{{ $meeting->name }}</td>
            <td>{{ $meeting->location }}</td>
            <td>{{ $meeting->latitude }}</td>
            <td>{{ $meeting->longitude }}</td>
            <td>{{ $meeting->meeting_time }}</td>
            <td>{{ $meeting->distance_time }}</td>
            <td>{{ $meeting->distance_kilometer,"KM" }}</td>
            <td>{{ $meeting->date }}</td>

          </tr>
          @endforeach
          @else
          <tr>
            <td colspan="9">No meeting Found!</td>
          </tr>

          @endif

        </tbody>
      </table>
      </div>
    </div>
  </div>
</div>



<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCTbYZF_kDxKNopcvej6oh-eVs1z9Xq2J0&libraries=places">    </script>

<script>
    $(document).ready(function(){
        var autocomplete;
        var to ='location';
        autocomplete=new google.maps.places.Autocomplete((document.getElementById(to)),{
            types:['geocode'],
        });

        google.maps.event.addListener(autocomplete,'place_changed',function(){
            var near_place=autocomplete.getPlace();

            jQuery("#latitude").val( near_place.geometry.location.lat() );
            jQuery("#longitude").val( near_place.geometry.location.lng() );
            $.getJSON("https://api.ipify.org/?format=json",function(data){
                let ip=data.ip;
                jQuery("#ip").val(ip);
                getCity(ip);
            });

        });

        //get meetings by date

        $('#date').change(function(){
          var date=$(this).val();
          $.ajax({
            url:"{{ route('getDateMeetings') }}",
            type:"GET",
            data:{'date':date},
            success:function(data){
              var html='';
              var meetings=data.meeting;
              if(meetings.length > 0){
                for(let i=0;i<meetings.length;i++){
                  html +=`
                  <tr>
                    <td>`+meetings[i]['id']+`</td>
                    <td>`+meetings[i]['name']+`</td>
                    <td>`+meetings[i]['location']+`</td>
                    <td>`+meetings[i]['latitude']+`</td>
                    <td>`+meetings[i]['longitude']+`</td>
                    <td>`+meetings[i]['meeting_time']+`</td>
                    <td>`+meetings[i]['distance_time']+`</td>
                    <td>`+meetings[i]['distance_kilometer']+`</td>
                    <td>`+meetings[i]['date']+`</td>


                    </tr>
                    `;
                }
              }
              else{
                html +=`
                <tr>
                  <td colspan="9">No meetings Found</td>
                  </tr>
                  `;
              }
              $(".tbody").html(html);
            }

          });
        });
        //

    });

    function getCity(ip){
        var req=new XMLHttpRequest();
        req.open("GET","http://ip-api.com/json/"+ip,true);
        req.send();

        req.onreadystatechange=function(){
            if(req.readyState==4 && req.status==200){
                var obj=JSON.parse(req.responseText);
                // console.log(obj);
                jQuery("#city").val(obj.city);
                calculateDistance();

            };
        };
    };

    function calculateDistance(){
        var to=jQuery("#city").val();
        var from=jQuery("#location").val();

        var service=new google.maps.DistanceMatrixService();
        service.getDistanceMatrix({

            origins:[to],
            destinations:[from],
            travelMode:google.maps.TravelMode.DRIVING,
            unitSystem:google.maps.UnitSystem.matric,
            avoidHighways:false,
            avoidTolls:false
            
        },callback);

    }

    function callback(response,status)
    {
        if(status !=google.maps.DistanceMatrixStatus.OK)
        {
            console.log("Something wrong");
        }
        else{
            if(response.rows[0].elements[0].status=="ZERO_RESULTS"){
                console.log('No roads');            }
            else{
                var distance=response.rows[0].elements[0].distance;
                var duration=response.rows[0].elements[0].duration;
                var distance_in_km=distance.value/1000;
                var distance_in_miute=duration.value/60;
                jQuery('#dkm').val(parseInt(distance.value/1000));
                jQuery('#dtime').val(parseInt(distance_in_miute));




                // console.log(duration,distance_in_km);
            }
        }
    }



</script>
@endsection()