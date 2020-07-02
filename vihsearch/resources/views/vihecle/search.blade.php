<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title></title>
		<meta name="description" content="">
		<meta name="viewport" content="width=device-width">

		<!-- Font Awesome -->
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css">
		<!-- Google Fonts -->
		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap">
		<!-- Bootstrap core CSS -->
		<link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.0/css/bootstrap.min.css" rel="stylesheet">
		<!-- Material Design Bootstrap -->
		<link href="https://cdnjs.cloudflare.com/ajax/libs/mdbootstrap/4.19.1/css/mdb.min.css" rel="stylesheet">


		<!-- JQuery -->
		<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
		<!-- Bootstrap tooltips -->
		<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.4/umd/popper.min.js"></script>
		<!-- Bootstrap core JavaScript -->
		<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.0/js/bootstrap.min.js"></script>
		<!-- MDB core JavaScript -->
		<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/mdbootstrap/4.19.1/js/mdb.min.js"></script>

		<title>Document</title>

		<link rel="stylesheet" href="css/mystyle.css">
	</head>
	<body>

		<div id="app" >
			<div class="searchHeaderDiv sticky-top">
				<div class="container">
					<div class="form-row">
						<div class="col-md-12 mb-3">
							<div v-for="tcateg in filterData.categs" class="custom-control custom-radio custom-control-inline">
								<input type="radio" :id="'categ'+tcateg.id" v-model="categ" :value="tcateg.id" name="customRadioInline1" class="custom-control-input">
								<label class="custom-control-label" :for="'categ'+tcateg.id">@{{tcateg.name}}</label>
							</div>
						</div>
					</div>

					<mdb-select
						outline
						v-model="filterData.categs"
						label="Example label"
						placeholder="choose your option"
					/>

					<div class="form-row">
						<div class="col-md-4 mb-3">
						</div>
						<div class="col-md-4 mb-3">
							<select class="mdb-select md-form colorful-select dropdown-primary" searchable="Search here.." v-model="producer" @change="filterChanged">
								<option value="" data-tokens="">
									All
								</option>

								<option v-for="prod in filterData.producers" :value="prod.id">
									@{{prod.name}}
								</option>
							</select>
						</div>

						<div class="col-md-4 mb-3">
							<select class="filter" v-model="model" @change="filterChanged" data-live-search="true">
								<option value="" data-tokens="">
									All
								</option>

								<option v-for="mod in filterData.models" :value="mod">
									@{{mod}}
								</option>
							</select>
						</div>
					</div>


					<div class="form-row">
						<div class="col-md-4 mb-3">
						</div>
						<div class="col-md-4 mb-3">
							<select class="filter" v-model="yearFrom" @change="filterChanged" data-live-search="true">
								<option value="" data-tokens="">
									All
								</option>

								<option v-for="year in yearRange" :value="year" :disabled="yearTo ? year>yearTo:false">
									@{{year}}
								</option>
							</select>
						</div>
						<div class="col-md-4 mb-3">
							<select class="filter" v-model="yearTo" @change="filterChanged" data-live-search="true">
								<option value="" data-tokens="">
									All
								</option>

								<option v-for="year in yearRange" :value="year"  :disabled="yearFrom ? year<yearFrom : false">
									@{{year}}
								</option>
							</select>
						</div>
					</div>

					<div class="form-row">
						<div class="col-md-4 mb-3">
						</div>
						<div class="col-md-4 mb-3">
							<select class="filter" v-model="sizeFrom" @change="filterChanged" data-live-search="true">
								<option value="0" data-tokens="">
									All
								</option>

								<option v-for="size in sizeRange" :value="size" :disabled="sizeTo ? size>sizeTo:false">
									@{{size}}
								</option>
							</select>
						</div>
						<div class="col-md-4 mb-3">
							<select class="filter" v-model="sizeTo" @change="filterChanged" data-live-search="true">
								<option value="99999" data-tokens="">
									All
								</option>

								<option v-for="size in sizeRange" :value="size"  :disabled="sizeFrom ? size<sizeFrom : false">
									@{{size}}
								</option>
							</select>
						</div>
					</div>
				</div>
			</div>
			<div class="container">
				<div class="form-row">
					<div class="col-md-12 mb-3">
						<div ref="resultsDiv">
							<viheclemodal ref="modal" :vid="modalVal" searching="false"></viheclemodal>
							<div v-for="result in results" class="result">
								<a href="#" @click.prevent="showModal" :vid="result.id">
									<vihecle :rdata="result"></vihecle>
								</a>
							</div>
						</div>
					</div>
				</div>

				<div v-if="noResults">
					Sorry, but no results were found.
				</div>

				<div v-if="searching">
					<i>Searching...</i>
				</div>
			</div>
		</div>

		<script src="js/app.js"></script>
	</body>
</html>