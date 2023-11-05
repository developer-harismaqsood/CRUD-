<template>
<div style="height:100%;">
    <div class="page-content">
	<Sidebar></Sidebar>
	<!-- Main content -->
        <div class="content-wrapper">
            <div class="content-inner">
                <div class="content">
                	
					<div class="row justify-content-center">
						<div class="col-xl-11">

		                	<div class="mb-0">
		    					<h1>Welcome To Company CRM -</h1>
						    	<div class="card-body">
						    	</div>
							</div>
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
            text: '',
        }
    },
    methods:
    {
    }
}

</script>