
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>

    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css">

    <!-- Latest compiled and minified JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/bootstrap-select.min.js"></script>

    <!-- (Optional) Latest compiled and minified JavaScript translation files -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/i18n/defaults-*.min.js"></script>

    <title>Document</title>
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-4">
                <div class="form-row">
                    <div class="col-md-4 mb-3">
                    </div>
                    <div class="col-md-8 mb-3">
                        <select class="selectpicker test">
                            <optgroup label="Picnic">
                              <option>Mustard</option>
                              <option>Ketchup</option>
                              <option>Relish</option>
                            </optgroup>
                            <optgroup label="Camping">
                              <option>Tent</option>
                              <option>Flashlight</option>
                              <option>Toilet Paper</option>
                            </optgroup>
                          </select>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="col-md-4 mb-3">
                    </div>

                    <div class="col-md-8 mb-3">
                        
                        <select class="selectpicker filter" data-live-search="true">
                            
                        </select>
                          
                    </div>
                </div>

                <div class="form-row">
                    <div class="col-md-4 mb-3">
                    </div>
                    <div class="col-md-8 mb-3">
                        <select class="selectpicker filter" data-live-search="true">
                            
                          </select>
                    </div>
                </div>

            </div>
            <div class="col-8">
                @foreach ($vihecles as $vihecle)
                <div>{{$vihecle->country_id}}</div>
                @endforeach

                <nav aria-label="...">
                    <ul class="pagination">
                      <li class="page-item disabled">
                        <span class="page-link">Previous</span>
                      </li>
                      <li class="page-item"><a class="page-link" href="#">1</a></li>
                      <li class="page-item active" aria-current="page">
                        <span class="page-link">
                          2
                          <span class="sr-only">(current)</span>
                        </span>
                      </li>
                      <li class="page-item"><a class="page-link" href="#">3</a></li>
                      <li class="page-item">
                        <a class="page-link" href="#">Next</a>
                      </li>
                    </ul>
                  </nav>
            </div>
        </div>
    </div>
    
    <script>

        function search()
        {
            $.ajax()
            
    </script>
</body>
</html>


