<template>
	<div style="height:100%;">
    	<div class="page-content">
			<Sidebar></Sidebar>
	        <div class="content-wrapper">
	            <div class="content-inner">
	            	<div class="content">
	            		<div class="row justify-content-center">
							<div class="col-xl-11">

			                    <!-- User lists -->
			                    <div class="card">
			                        <div class="card-header">
			                            <h5 class="mb-0">Company Managment</h5>
			                             <button type="button" href="" class="btn btn-flat-primary mt-3" @click="DOM.CreateShow = true; DOM.formTitle = 'create'" v-if="!DOM.CreateShow">Create Company</button>

			                        </div>
			                        <table class="table" v-if="!DOM.CreateShow">
			                            <thead>
			                                <tr>
			                                    <th>#</th>
			                                    <th>Name</th>
			                                    <th>Action</th>
			                                </tr>
			                            </thead>
			                            <tbody>
			                                <tr v-for="(company,index) in displayedPosts">
			                                    <td> {{ index + 1 }}</td>
			                                    <td><a href="#">{{company.name}}</a></td>
			                                    <td>
			                                        <a href=""> 
		                                                <button type="button" class="btn btn-flat-primary" 
		                                                @click.stop.prevent="edit(company.id); DOM.formTitle = 'edit'">Edit</button>
			                                        </a>
			                                        <a href=""> 
		                                                <button type="button" class="btn btn-flat-danger" 
		                                                @click.stop.prevent="remove(company.id)">
		                                                	Delete
		                                                </button>
			                                        </a>
			                                    </td>
			                                </tr>
			                            </tbody>
			                        </table>
			                    </div>
			                    <!-- User lists -->

			        <!-- pagination -->
                        <div class="row justify-content-center mb-4" v-if="!DOM.CreateShow">
                            <div class="col-12">
                                <nav aria-label="Page navigation example">
                                    <ul class="pagination" style="margin-left: 50%;">
                                        <li class="page-item">
                                            <button type="button" class="page-link" v-if="page != 1" @click="page--">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                     fill="currentColor" class="bi bi-arrow-bar-left"
                                                     viewBox="0 0 16 16">
                                                    <path fill-rule="evenodd"
                                                          d="M12.5 15a.5.5 0 0 1-.5-.5v-13a.5.5 0 0 1 1 0v13a.5.5 0 0 1-.5.5zM10 8a.5.5 0 0 1-.5.5H3.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L3.707 7.5H9.5a.5.5 0 0 1 .5.5z"/>
                                                </svg>
                                            </button>
                                        </li>
                                        <li class="page-item">
                                            <button type="button" class="page-link"
                                                    v-for="pageNumber in pages.slice(page-1, page+5)"
                                                    @click="page = pageNumber" style="float:left; height: 100%;"> {{pageNumber}}
                                            </button>
                                        </li>
                                        <li class="page-item">
                                            <button type="button" @click="page++" v-if="page < pages.length"
                                                    class="page-link" >
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                     fill="currentColor" class="bi bi-arrow-bar-right"
                                                     viewBox="0 0 16 16">
                                                    <path fill-rule="evenodd"
                                                          d="M6 8a.5.5 0 0 0 .5.5h5.793l-2.147 2.146a.5.5 0 0 0 .708.708l3-3a.5.5 0 0 0 0-.708l-3-3a.5.5 0 0 0-.708.708L12.293 7.5H6.5A.5.5 0 0 0 6 8zm-2.5 7a.5.5 0 0 1-.5-.5v-13a.5.5 0 0 1 1 0v13a.5.5 0 0 1-.5.5z"/>
                                                </svg>
                                            </button>
                                        </li>
                                    </ul>
                                </nav>
                            </div>
                        </div>
                        <!-- pagination -->

			                    <!-- Create Form -->

									<div class="card" v-if="DOM.CreateShow">
										<div class="card-header">
											<h5 class="mb-0">{{ DOM.formTitle === 'create' ? 'Create Company' : 'Edit Company' }}</h5>
										</div>

										<div class="card-body border-top">
											<form action="#" id="create-user">
												<div class="row mb-3">
													<label class="col-lg-3 col-form-label">Name</label>
													<div class="col-lg-9">
														<input type="text" class="form-control" placeholder="Name"
														 	v-validate="'required'" name="name" v-model="Form.name">
		                                                    <div class="help-block with-errors error-color">
		                                                    	<span>{{ errors.first('name') }}</span>
		                                                	</div>
													</div>
												</div>

												<div class="row mb-3">
													<label class="col-lg-3 col-form-label">Email</label>
													<div class="col-lg-9">
														<input type="text" class="form-control" placeholder="Email"v-model="Form.email">
													</div>
												</div>

												<div class="row mb-3">
													<label class="col-lg-3 col-form-label">
													Logo</label>
													<div class="col-lg-4">
														<input type="file" class="form-control"
														 onclick="this.value=null"
														@change="upload($event)">
													</div>
													<div class="col-lg-5">
														  <img class="header-logo-image float-left" for="uploadLogo"
                                                         :src="Form.src"
                                                         v-if="Form.src" width="50" height="40">
													</div>
													<div class="help-block with-errors error-color" v-if="DOM.logoError">
		                                                    	<span>{{ DOM.logoError }}</span>
		                                                	</div>

												</div>
                                                   

												<div class="row mb-3">
													<label class="col-lg-3 col-form-label">Website</label>
													<div class="col-lg-9">
														<input type="text" class="form-control" placeholder="Website" v-model="Form.website">
													</div>
												</div>


												<div class="text-end">
													<button type="button" class="btn btn-danger" @click="DOM.CreateShow = false">
															Cancel<i class="ms-2"></i>
													</button>

													<button type="button" class="btn btn-primary" @click="Create">
														Submit
														<i class="ph-paper-plane-tilt ms-2"></i>
													</button>


												</div>
											</form>
										</div>
									</div>

			                    <!-- Create Form -->
			                </div>
			            </div>
	                </div>
	            </div>
	        </div>
		</div>
</div>
</template>


<script type="text/javascript">
	
	import Sidebar from './includes/sidebar.vue'

	export default {
    components: {
        Sidebar
    },
     props: ['users','me','companies'],
    data () {
        return{
        	Form:{
        		id:'',
        		name:'',
            	email:'',
            	logo:'',
            	website:'',
            	src:''
        	},
            DOM:{
            	CreateShow:false,
            	formTitle:'',
            	LogoStatus:'',
            	logoError:''
            },
            posts : [],
			page: 1,
			perPage: 4,
			pages: [],	
        }
    },
    methods:
    {
    	Create()
    	{
                var self = this;

                this.$validator.validate('name');

                if ( !this.Form.name )
                	return;
                	
                axios.post('api/company/create-company', {
                    data: self.Form,
                    type:this.DOM.LogoStatus
                }).then(res => {

                	self.companies = res.data.data;

                	if(res.data.status == 'success')
                	{
                		Vue.$toast.success(res.data.message, {timer: 8});
                		self.DOM.CreateShow = false;

                		self.Form.id 	= '';
	                	self.Form.name  = '';
	                	self.Form.email = '';
	                	self.Form.website = '';
	                	self.Form.logo = '';
                	}
                });
    	},
    	edit(id)
    	{
    		this.DOM.CreateShow = true;

    		axios.get('api/company/get/'+id, {
                }).then(res => {
                	this.Form = res.data.message
                	this.Form.src = res.data.message.logo
                })
    	},
    	remove(id)
    	{
    		 axios.get('api/company/remove-company/'+id, {
                }).then(res => {
                	this.companies = res.data.message
                	Vue.$toast.success('company has been deleted', {
                        position: 'top'
                    });
                })
    	},

    	upload(event) 
    	{
                // this is for get image base54 to save
                var fileReader = new FileReader();
                fileReader.readAsDataURL(event.target.files[0]);


                //this is for check image size
                let img = new Image();
                img.src = window.URL.createObjectURL(event.target.files[0]);


                // get file size
                var fsize = event.target.files[0].size;
                var file = Math.round((fsize / 1024));

                if (file <= 25000) {
                    fileReader.onload = (e) => {
                        img.onload = () => {
                            this.Form.logo = '';
                            var extension = '';
                            var base64Vlaues = '';

                            if(img.width < 100 && img.height < 100){
                               this.validations.logo = 'The image is too small. Height must be 100 x 100';
                            }
                            else
                            {
                                this.Form.src = e.target.result;

                                // get Extension
                                base64Vlaues = e.target.result.split(';')[0].split('/');
                                extension = base64Vlaues[1];

                                var time = new Date().getTime();


                                this.Form.logo = "logo-" + time + '.' + extension;
                                alert(this.Form.logo);
                                this.DOM.LogoStatus = 'new';
                            }
                        }
                    }
                } else {
                    this.DOM.logoError = 'File size maximum 25mb allow';
                }
            },
            paginate(posts) {
                let page = this.page;
                let perPage = this.perPage;
                let from = (page * perPage) - perPage;
                let to = (page * perPage);
                return posts.slice(from, to);
            },
            setPages() {
                let numberOfPages = Math.ceil(this.posts.length / this.perPage);
                for (let index = 1; index <= numberOfPages; index++) {
                    this.pages.push(index);
                }
            },
        },
        computed:
        {
            displayedPosts() {
                return this.paginate(this.posts);
            }
        },
	    watch: {
	        posts() {
	            this.setPages();
	        }
	    },
	    filters: {
	        trimWords(value) {
	            return value.split(" ").splice(0, 20).join(" ") + '...';
	        }
	    },
        mounted()
         {
	        this.posts = this.companies;

	    }
   
}

</script>