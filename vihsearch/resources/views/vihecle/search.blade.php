<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title></title>
		<meta name="description" content="">
		<meta name="viewport" content="width=device-width">
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

		<link rel="stylesheet" href="css/slider.css">
	</head>
	<body>

		<div id="app" class="container">
			
			<div class="form-row">
				<div class="col-md-4 mb-3">
				</div>
				<div class="col-md-8 mb-3">
					<select class="filter" v-model="producer" @change="filterChanged" data-live-search="true">
						<option value="" data-tokens="">
							All
						</option>

						<option v-for="prod in filterData.producers" :value="prod.id">
							@{{prod.name}}
						</option>
					</select>
				</div>
			</div>

			<div class="form-row">
				<div class="col-md-4 mb-3">
				</div>
				<div class="col-md-8 mb-3">
					<select class="filter" v-model="model" @change="filterChanged" data-live-search="true">
						<option value="" data-tokens="">
							All
						</option>

						<option v-for="mod in filterData.models" :value="mod.model">
							@{{mod.model}}
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
				<div class="col-md-12 mb-3">
					<div ref="resultsDiv">
						<div v-for="result in results" class="result">
							@{{result.producer}}
							@{{result.series}}
							@{{result.size}}
							@{{result.config}}
							@{{result.year}}
							@{{result.country}}
							<br clear="left">
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

		<script src="js/app.js"></script>
	</body>
</html>