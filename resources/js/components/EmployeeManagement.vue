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
			                            <h5 class="mb-0">Employee Managment</h5>
			                             <button type="button" href="" class="btn btn-flat-primary mt-3" 
			                             		@click="DOM.CreateShow = true; DOM.formTitle = 'create'" 
			                             		v-if="!DOM.CreateShow">Create Employee</button>

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
			                                <tr v-for="(employee,index) in employees">
			                                    <td> {{ index + 1 }}</td>
			                                    <td><a href="#">{{employee.first_name}} {{employee.last_name}}</a></td>
			                                    <td>
			                                        <a href=""> 
			                                            <div class="mb-3 py-2">
			                                                <button type="button" class="btn btn-flat-primary" 
			                                                @click.stop.prevent="edit(employee.id); DOM.formTitle = 'edit'">Edit</button>
			                                                <button type="button" class="btn btn-flat-danger" 
			                                                @click.stop.prevent="remove(employee.id)">
			                                                	Delete
			                                                </button>
			                                            </div>
			                                        </a>
			                                    	
			                                    </td>
			                                </tr>
			                            </tbody>
			                        </table>
			                    </div>
			                    <!-- User lists -->

			                    <!-- Create Form -->

									<div class="card" v-if="DOM.CreateShow">
										<div class="card-header">
											<h5 class="mb-0">{{ DOM.formTitle === 'create' ? 'Create Employee' : 'Edit Employee' }}</h5>	
										</div>

										<div class="card-body border-top">
											<form action="#" id="create-user">
												<div class="row mb-3">
													<label class="col-lg-3 col-form-label">First Name</label>
													<div class="col-lg-9">
														<input type="text" class="form-control" placeholder="First Name"
														 	v-validate="'required'" name="first name" v-model="Form.first_name">
		                                                    <div class="help-block with-errors error-color">
		                                                    	<span>{{ errors.first('first name') }}</span>
		                                                	</div>
													</div>
												</div>

												<div class="row mb-3">
													<label class="col-lg-3 col-form-label">Last Name</label>
													<div class="col-lg-9">
														<input type="text" class="form-control" placeholder="Last Name"
														 	v-validate="'required'" name="last name" v-model="Form.last_name">
		                                                    <div class="help-block with-errors error-color">
		                                                    	<span>{{ errors.first('last name') }}</span>
		                                                	</div>
													</div>
												</div>

												<div class="row mb-3">
													<label class="col-lg-3 col-form-label">Email</label>
													<div class="col-lg-9">
														<input type="text" class="form-control" placeholder="Last Name"
														 	v-validate="'required'" name="email" v-model="Form.email">
		                                                    <div class="help-block with-errors error-color">
		                                                    	<span>{{ errors.first('email') }}</span>
		                                                	</div>
													</div>
												</div>


												<div class="row mb-3">
													<label class="col-lg-3 col-form-label">Phone</label>
													<div class="col-lg-9">
														<input type="text" class="form-control" placeholder="Last Name"
														 	v-validate="'required'" name="phone" v-model="Form.phone">
		                                                    <div class="help-block with-errors error-color">
		                                                    	<span>{{ errors.first('phone') }}</span>
		                                                	</div>
													</div>
												</div>
												

												<div class="row mb-3">
													<label class="col-lg-3 col-form-label">Company:</label>
													<div class="col-lg-9">
														<select class="form-control form-control-select2 user-role" 
																v-model="Form.company_id"
																v-validate="'required'" name="company">
															<optgroup label="Select company">
																<option value="null">Select</option>
																<option :value="company.id" v-for="company in companies">{{company.name}}</option>
															</optgroup>
														</select>
		                                                <div class="help-block with-errors error-color">
		                                                	<span>{{ errors.first('company') }}</span>
		                                            	</div>
													</div>
												</div>


												<div class="text-end">
													<button type="button" class="btn btn-danger" @click="DOM.CreateShow = false">
															Cancel<i class="ms-2"></i>
													</button>

													<button type="button" class="btn btn-primary" @click="Create">
														Create
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
     props: ['employees','me','companies'],
    data () {
        return{
        	Form:
        	{
        		id:'',
        		first_name:'',
            	last_name:'',
            	phone:'',
            	email:'',
            	company_id:null
        	},
            DOM:{
            	CreateShow:false,
            	formTitle:''
            }
        }
    },
    methods:
    {
    	Create()
    	{
                var self = this;

                this.$validator.validate('first name');
                this.$validator.validate('last name');

                if ( !this.Form.first_name && !this.Form.last_name )
                	return;
                	
                axios.post('api/employee/create-employee', {
                    data: self.Form
                }).then(res => {

                	self.employees = res.data.data;

                	if(res.data.status == 'success')
                	{
                		Vue.$toast.success(res.data.message, {timer: 8});
                		self.DOM.CreateShow = false;
                		
	            		self.Form.id 	= '';
	                	self.Form.first_name  = '';
	                	self.Form.last_name  = '';
	                	self.Form.email = '';
	                	self.Form.phone = '';
	                	self.Form.company_id = null;
                	}

                });
    	},
    	edit(id)
    	{
    		this.DOM.CreateShow = true;

    		axios.get('api/employee/get/'+id, {
                }).then(res => {
                	this.Form = res.data.message
                })
    	},
    	remove(id)
    	{
    		 axios.get('api/employee/remove-employee/'+id, {
                }).then(res => {
                	this.employees = res.data.message
                	Vue.$toast.success('Employee has been deleted', {
                        position: 'top'
                    });
                })
    	},
    }
}

</script>