<template>
    <div class="flex h-screen bg-gray-200 font-roboto">
      <Sidebar />
  
      <div class="flex-1 flex flex-col overflow-hidden">
        <Header :title="title"/>
        
  
        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-200">
          <div class="container mx-auto px-6 py-8">
            <div class="mt-4">
                <div class="p-6 bg-white rounded-md shadow-md">
                <h2 class="text-lg font-semibold text-gray-700 capitalize">
                    Add Department
                </h2>

                <form @submit.prevent="submit">
                    <div class="grid grid-cols-1 gap-6 mt-4 sm:grid-cols-2">
                        <div>
                            <label class="text-gray-700" for="username">Department Name</label>
                            <input
                                class="w-full mt-2 border-gray-200 rounded-md focus:border-indigo-600 focus:ring focus:ring-opacity-40 focus:ring-indigo-500"
                                type="text"
                                v-model="formObj.name"
                            />
                            <span v-if="error.name" class="text-red-600">Please enter Department name</span>
                        </div>

                        <div>
                            <label class="text-gray-700" for="emailAddress"
                            >Email</label
                            >
                            <input
                                class="w-full mt-2 border-gray-200 rounded-md focus:border-indigo-600 focus:ring focus:ring-opacity-40 focus:ring-indigo-500"
                                type="email"
                                v-model="formObj.email"
                            />
                            <span v-if="error.email" class="text-red-600">Please enter email</span>
                        </div>
                        <div>
                            <label class="text-gray-700" for="passwordConfirmation"
                            >Image</label
                            >
                            <input
                                class=" mt-2 block w-full text-sm text-indigo-500 
                                    file:mr-4 file:py-2 file:px-4
                                    file:rounded-full file:border-0
                                    file:text-sm file:font-semibold
                                    file:bg-indigo-600 file:text-white
                                    hover:file:bg-indigo-500
                                " 
                                type="file"
                                @input="formObj.image = $event.target.files[0]"
                            />
                            <span v-if="error.image" class="text-red-600">Please select image</span>
                        </div>
                    </div>
                    <div class="flex justify-end mt-4">
                        <button
                            class="px-4 py-2 text-gray-200 bg-indigo-800 rounded-md hover:bg-indigo-600 focus:outline-none focus:bg-indigo-700"
                            type="submit"
                        >
                            Save
                        </button>
                    </div>
                </form>
                </div>
            </div>
          </div>
        </main>
      </div>
    </div>
  </template>
  
  <script>
  import Header from "../Components/Header.vue";
  import Sidebar from "../Components/Sidebar.vue";
  export default{
    data(){
      return{
        title:"Add Department",
        formObj:{
            name:null,
            email:null,
            image:null
        },
        error:{
            name:false,
            email:false,
            image:false,
        }
      }
    },
    components:{
        Header,
        Sidebar
    },
    methods:{
        submit(){

            this.formObj.name == null ? this.error.name = true : this.error.name = false;
            this.formObj.email == null ? this.error.email = true : this.error.email = false;
            this.formObj.image == null ? this.error.image = true : this.error.image = false;

            if (this.error.name || this.error.email|| this.error.image) {
                return false;
            }
            
            this.$inertia.post(route('store.department'),this.formObj,{
                onSuccess: (response)=>{
                    this.$toast.success(
                        this.$page.props.flash.success,
                        {
                            position:"top"
                        }
                    )
                }
            });
        }
        
    }
  }
  </script>
  