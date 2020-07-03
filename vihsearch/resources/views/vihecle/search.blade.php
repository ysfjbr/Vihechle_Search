<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title></title>
		<meta name="description" content="">
		<meta name="viewport" content="width=device-width">

		<title>Document</title>

		<link rel="stylesheet" href="css/mystyle.css">
	</head>
	<body>

		<div id="app" >
			<div class="searchHeaderDiv sticky-top">
				<div class="container">
					
					<div class="form-row">
						<div class="col-md-4 mb-3">
							<v-select v-model="categ" class="categ_select" placeholder="Select Category" :reduce="item => item.code" :options="filterData.categs || options" />
						</div>
						<div class="col-md-4 mb-3">
							<v-select v-model="producer" placeholder="Select Producer"  :reduce="item => item.code" :options="filterData.producers || options" />
						</div>
						<div class="col-md-4 mb-3">
							<v-select v-model="model" :disabled="producer===null" placeholder="Select Model"  :reduce="item => item.code"  :options="filterData.models || options" />
						</div>
					</div>

					<div class="form-row">
						<div class="col-md-6 mb-3">
							<div class="input-group mb-3">
								<div class="input-group-prepend">
									<span class="input-group-text" id="">Engine Size Range</span>
								  </div>
								<div class="custom-select">
									<v-select v-model="sizeFrom" placeholder="All" :reduce="item => item.code" :options="sizeRange || options" /><option selected>Choose...</option>
								</div>
								
								<div class="custom-select">
									<v-select v-model="sizeTo"  placeholder="All"  :reduce="item => item.code" :options="sizeRange || options" />
								</div>
							</div>
						</div>
						<div class="col-md-6 mb-3">
							<div class="input-group mb-3">
								<div class="input-group-prepend">
									<span class="input-group-text" id="">Model Year Range</span>
								  </div>
								<div class="custom-select">
									<v-select v-model="yearFrom"  placeholder="All"  :reduce="item => item.code"  :options="yearRange || options" />
								</div>
								
								<div class="custom-select">
									<v-select v-model="yearTo"  placeholder="All"  :reduce="item => item.code"  :options="yearRange || options" />
								</div>
							</div>
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