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
			                            <h5 class="mb-0">User Managment</h5>
			                             <button type="button" href="" class="btn btn-flat-primary mt-3" 
			                             		@click="DOM.CreateShow = true; DOM.formTitle = 'create'" 
			                             		v-if="!DOM.CreateShow">Create User</button>

			                        </div>
			                        <table class="table datatable-basic" v-if="!DOM.CreateShow">
			                            <thead>
			                                <tr>
			                                    <th>#</th>
			                                    <th>Name </th>
			                                    <th>Email</th>
			                                    <th>Edit</th>
			                                    <th>Delete</th>
			                                </tr>
			                            </thead>
			                            <tbody>
			                                <tr v-for="(user,index) in users">
			                                    <td> {{ index + 1 }}</td>
			                                    <td><a href="#">{{user.name}}</a></td>
			                                    <td><a href="#">{{user.email}}</a></td>
			                                    <td>
			                                        <a href=""> 
			                                            <div class="mb-3 py-2">
			                                                <button type="button" class="btn btn-flat-primary" 
			                                                @click.stop.prevent="editUser(user.id); DOM.formTitle = 'edit'">Edit</button>
			                                            </div>
			                                        </a>
			                                    </td>
			                                    <td>
			                                    	 <div class="mb-3 py-2">
			                                                <button type="button" class="btn btn-flat-danger" @click.stop.prevent="removeUser(user.id)">
			                                                	Delete
			                                                </button>
			                                            </div>
			                                    </td>
			                                </tr>
			                            </tbody>
			                        </table>
			                    </div>
			                    <!-- User lists -->

			                    <!-- Create Form -->

									<div class="card" v-if="DOM.CreateShow">
										<div class="card-header">
											<h5 class="mb-0">{{ DOM.formTitle === 'create' ? 'Create User' : 'Edit User' }}</h5>
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
														<input type="text" class="form-control" placeholder="Email"
														 	v-validate="'required'" name="email" v-model="Form.email">
		                                                    <div class="help-block with-errors error-color">
		                                                    	<span>{{ errors.first('email') }}</span>
		                                                	</div>

		                                                	 <div class="help-block with-errors error-color" v-if="errorian.emailExist">
		                                                    	<span>{{ errorian.emailExist }}</span>
		                                                	</div>

		                                                	
													</div>
												</div>

												<div class="row mb-3">
													<label class="col-lg-3 col-form-label">Password:</label>
													<div class="col-lg-9">
														<input type="password" class="form-control" placeholder="Your strong password"
															v-validate="'required'" name="password" v-model="Form.password">
		                                                    <div class="help-block with-errors error-color">
		                                                    	<span>{{ errors.first('password') }}</span>
		                                                	</div>
													</div>
												</div>
												
												<div class="text-end">
													<button type="button" class="btn btn-danger" @click="DOM.CreateShow = false">
															Cancel<i class="ms-2"></i>
													</button>

													<button type="button" class="btn btn-primary" @click="CreateUser">
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
     props: ['users','me'],
    data () {
        return{
        	Form:{
        		id:'',
        		name:'',
            	email:'',
            	password:''
        	},
            DOM:{
            	CreateShow:false,
            	formTitle:'',
            	designateShow:false
            },
            errorian:{
            	emailExist:''
            }
        }
    },
    methods:
    {
    	CreateUser()
    	{
                var self = this;

                this.$validator.validate('name');
                this.$validator.validate('email');
                this.$validator.validate('password');

                if ( !this.Form.name && !this.Form.email && !this.Form.password )
                	return;
                	
                axios.post('api/user/create-user', {
                    data: self.Form
                }).then(res => {

                	self.users = res.data.data;

                	if(res.data.status == 'success')
                	{
                		Vue.$toast.success(res.data.message, {timer: 8});
                		self.DOM.CreateShow = false;

                		self.Form.id 	= '';
	                	self.Form.name  = '';
	                	self.Form.email = '';
	                	self.Form.password = '';
                	}

                	if(res.data.status == 'error')
                	{
                		// setTimeout(function () {
		                // 	self.errorian.emailExist = res.data.message;
		                // }, 3000);
                	}
                });
    	},
    	editUser(id)
    	{
    		this.DOM.CreateShow = true;

    		axios.get('api/user/get/'+id, {
                }).then(res => {
                	this.Form = res.data.message
                })
    	},
    	removeUser(id)
    	{
    		 axios.get('api/user/remove-user/'+id, {
                }).then(res => {
                	this.users = res.data.message
                	Vue.$toast.success('User has been deleted', {
                        position: 'top'
                    });
                })
    	},
    }
}

</script>