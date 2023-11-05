<template>
	<div style="height:100%;">
    	<div class="page-content">
			<Sidebar :meDetail="me" ></Sidebar>
	        <div class="content-wrapper">
	            <div class="content-inner">
	            	<div class="content">
	            		<div class="row justify-content-center">
							<div class="col-xl-11">

			                    <!-- Passowrd change-->
								<div class="card">
									<div class="card-header">
										<h5 class="mb-0">Password Change</h5>
									</div>

									<div class="card-body border-top">
										<form action="#" id="create-user">
											<div class="row mb-3">
												<label class="col-lg-3 col-form-label">Password</label>
												<div class="col-lg-9">
													<input type="text" class="form-control" placeholder="Password"
													 	v-validate="'required|min:8'" name="password" v-model="Form.password" ref="password">
					                                    <div class="help-block with-errors error-color">
					                                    	<span>{{ errors.first('password') }}</span>
					                                	</div>
												</div>
											</div>
											<div class="row mb-3">
												<label class="col-lg-3 col-form-label">Re-Enter Password</label>
												<div class="col-lg-9">
													<input type="text" class="form-control" placeholder="Password Confirmation"
													 		v-validate="'required|confirmed:password|min:8'" name="password confirmation" 
													 		v-model="Form.confirm_password" data-vv-as="password">
					                                    <div class="help-block with-errors error-color">
					                                    	<span>{{ errors.first('password confirmation') }}</span>
					                                	</div>
												</div>
											</div>

											
											<div class="text-end">
												<button type="button" class="btn btn-primary" @click="Create">
													Change
													<i class="ph-paper-plane-tilt ms-2"></i>
												</button>

											</div>
										</form>
									</div>
								</div>
			                    <!-- Passowrd change-->
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
     props: ['me'],
    data () {
        return{
        	Form:
        	{
        		id:'',
        		password:'',
            	confirm_password:''
        	}
        }
    },
    methods:
    {
    	Create()
    	{
                var self = this;

                this.$validator.validate('password');
                this.$validator.validate('password confirmation');

                if ( !this.Form.password && !this.Form.confirm_password )
                	return;
                	
                axios.post('api/my-account/password-change', {
                    data: self.Form
                }).then(res => {

                	if(res.data.status == 'success')
                	{
                		Vue.$toast.success(res.data.message, {timer: 10});
                		
	            		self.Form.id 	= '';
	                	self.Form.password  = '';
	                	self.Form.confirm_password = '';
                		
                		this.$validator.reset();
                	}
                });
    	}
    },
    mounted(){
    	this.Form.id = this.me.id;
    }
}

</script>