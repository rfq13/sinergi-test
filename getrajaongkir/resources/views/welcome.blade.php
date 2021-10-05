@php
    // dd($data);
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
    </head>
    <body>
        <div class="container-fluid py-4">
            <p class="text-center">asal : DI Yogjakarta -> tujuan : <span id="tujuan">-</span>
                <br><span id="loader" class="d-none" style="position: absolute">mohon tungu...</span>
            </p>
            
            <div class="row py-2 mt-6">
                <div class="col">
                    <select class="custom-select courier" onchange="getCost()">
                        <option value="jne">jne</option>
                        <option value="pos">pos</option>
                        <option value="tiki">tiki</option>
                    </select>
                </div>
                <div class="col">
                    <select class="custom-select" onchange="getCity(event)">
                        <option selected>Provinces</option>
                        @foreach ($data as $prov)
                        <option value="{{ $prov->province_id }}">{{ $prov->province }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col">
                    <select class="custom-select d-none cities" onchange="getCost()"></select>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-6">
                    <div class="list-group costs d-none">
                        <a href="#" class="list-group-item list-group-item-action active">
                          <div class="d-flex w-100 justify-content-between">
                            <h5 class="mb-1">List group item heading</h5>
                            <small>3 days ago</small>
                          </div>
                          <p class="mb-1">Some placeholder content in a paragraph.</p>
                        </a>
                        <a href="#" class="list-group-item list-group-item-action">
                          <div class="d-flex w-100 justify-content-between">
                            <h5 class="mb-1">List group item heading</h5>
                            <small class="text-muted">3 days ago</small>
                          </div>
                          <p class="mb-1">Some placeholder content in a paragraph.</p>
                        </a>
                        <a href="#" class="list-group-item list-group-item-action">
                          <div class="d-flex w-100 justify-content-between">
                            <h5 class="mb-1">List group item heading</h5>
                            <small class="text-muted">3 days ago</small>
                          </div>
                          <p class="mb-1">Some placeholder content in a paragraph.</p>
                        </a>
                      </div>
                </div>
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-/bQdsTh/da6pkI1MST/rWKFNjaCP5gBSY4sEBT38Q/9RBh9AH40zEOg7Hlq2THRZ" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.min.js" integrity="sha384-+YQ4JLhjyBLPDQt//I+STsc9iw4uQqACwlvpslubQzn4u2UU2UFM80nGisd026JF" crossorigin="anonymous"></script>


        <script>
            function getCity(e) {
                const element = $(e.target);
                $(".cities").addClass('d-none');
                $("#loader").removeClass('d-none');
                $(".costs").addClass('d-none');
                $.get("{{ route('get-city') }}",{province:element.val()},(data)=>{
                    content = '';
                    data.data.forEach(city => {
                        content += `<option value="${city.city_id}">${city.type} ${city.city_name}</option>`;
                    });

                    $(".cities").html(content).removeClass('d-none');
                    $("#loader").addClass('d-none');
                })
            }

            function getCost() {
                const city = $(".cities");
                const courier = $(".courier");

                city.prop('disbaled',true)
                courier.prop('disbaled',true)
                if (!city.val()) return;

                $(".costs").addClass('d-none');
                $("#loader").removeClass('d-none');
                $.get("{{ route('get-costs') }}",{
                        origin:501,
                        destination:city.val(),
                        weight:500,
                        courier:[courier.val()]
                    },(data)=>
                    {
                        // console.log(data); return;
                        content = '';
                        data.forEach(courier => {
                            courier.costs.forEach(cost => {
                                ongkir = cost.cost;
                                content += `
                                <a href="#" class="list-group-item list-group-item-action">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h5 class="mb-1"><strong style="text-transform: uppercase;">${courier.code}</strong> ${cost.service}</h5>
                                        <small>${ formatRupiah(ongkir.value.toString()) }</small>
                                    </div>
                                <p class="">${cost.description}.</p>
                                <p class="mb-1">estimasi pengiriman : ${ongkir.etd} hari (${ongkir.etd_text}).</p>
                                </a>
                                `;
                                
                            });
                        });

                        $(".costs").html(content).removeClass('d-none');

                        city.prop('disbaled',false)
                        courier.prop('disbaled',false)

                        $("#tujuan").text(city.find("option:selected" ).text());
                        $("#loader").addClass('d-none');
                })
            }

            function formatRupiah(angka){
                var number_string = angka.replace(/[^,\d]/g, ''),
                split   		= number_string.split(','),
                sisa     		= split[0].length % 3,
                rupiah     		= split[0].substr(0, sisa),
                ribuan     		= split[0].substr(sisa).match(/\d{3}/gi);
    
                // tambahkan titik jika yang di input sudah menjadi angka ribuan
                if(ribuan){
                    separator = sisa ? '.' : '';
                    rupiah += separator + ribuan.join('.');
                }
    
                rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
                return 'Rp' + rupiah;
            }
        </script>
    </body>
</html>
