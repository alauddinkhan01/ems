<template>
    <div class="flex h-screen bg-gray-200 font-roboto">
      <Sidebar />
  
      <div class="flex-1 flex flex-col overflow-hidden">
        <Header :title="title"/>
        
  
        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-200">
          <div class="container mx-auto px-6 py-8">
            <div class="mt-4">
                <Link 
                    class="px-4 py-2 text-gray-200 bg-indigo-800 rounded-md hover:bg-indigo-500 focus:outline-none focus:bg-gray-700 float-right"
                    :href="route('create.department')"

                >
                    Add Department
                </Link><br>
                <div class="mt-6">
                    <div class="my-6 overflow-hidden bg-white rounded-md shadow">
                    <table class="w-full text-left border-collapse">
                        <thead class="border-b">
                        <tr>
                            <th
                            class="px-5 py-3 text-sm font-medium text-gray-100 uppercase bg-indigo-800"
                            >
                            Image
                            </th>
                            <th
                            class="px-5 py-3 text-sm font-medium text-gray-100 uppercase bg-indigo-800"
                            >
                            Department Name
                            </th>
                            <th
                            class="px-5 py-3 text-sm font-medium text-gray-100 uppercase bg-indigo-800"
                            >
                            Email
                            </th>
                            <th
                            class="px-5 py-3 text-sm font-medium text-gray-100 uppercase bg-indigo-800"
                            >
                            Action
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr
                            v-for="(department,index) in departments.data"
                            :key="department.id"
                            class="hover:bg-gray-200"
                        >
                            <td class="px-6 py-4 text-lg text-gray-700 border-b">
                                <img 
                                  :src=" '/department/'+department.image"  
                                  class="object-none h-20 w-20 border-radius: 3rem;" 
                                  style="border-radius:40px" alt=""
                                  @click="showImage(department)"
                                >
                            </td>
                            <td class="px-6 py-4 text-lg text-gray-700 border-b">
                                <input 
                                  type="text" 
                                  v-model=" department.name" 
                                  style="border:none"
                                  @blur="updateField(department.id,'name',department.name)"
                                >
                            
                            </td>
                            <td class="px-6 py-4 text-gray-500 border-b">
                              <input 
                              type="email" 
                              v-model=" department.email" 
                              style="border:none" 
                              @blur="updateField(department.id,'email',department.email)"
                              >
                              <span v-if="errors.value">{{ errors.value }}</span>
                            </td>
                            <td class="px-6 py-4 text-gray-500 border-b">
                              <button
                                @click="deleteDepartment(department.id)"
                                class="px-4 ml-2 py-2 text-red-200 bg-red-700 rounded-md hover:bg-red-500 focus:outline-none focus:bg-red-700"
                              >Delete</button>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    <Pagination :links="departments.links" :count="departments.total" :totalPages="departments.last_page"/>

                    </div>
                </div>
            </div>
            <slot />
          </div>
        </main>
      </div>
      <ModalsContainer />
    </div>
  </template>
  
  <script>
  import Header from "../Components/Header.vue";
  import Sidebar from "../Components/Sidebar.vue";
  import Pagination from '../Components/Pagination.vue';
  import ImageModal from "../Modals/ImageModal.vue"
  import { $vfm } from 'vue-final-modal';


  export default{
    props:['departments','errors'],

    data(){
      return{
        title:"Departments"
      }
    },
    components:{
      Header,
      Sidebar,
      Pagination,
      ImageModal
    },
    methods:{
      updateField(id,field,value){
        this.axios.post(route('update.department'),{
          id:id,
          field:field,
          value:value,
        }).then((response)=>{
          this.$toast.success(
              response.data.success,
              {
                  position:"top"
              }
          )
          
        }).catch((err)=>{
          console.log(err.response.data.message);
          this.$toast.error(
              err.response.data.message,
              {
                  position:"top"
              }
          )
        })
      },
      deleteDepartment(id){
        if(confirm("Do you really want to delete this Department?")){
          this.axios.delete(route('delete.department',id)).then((response)=>{
            this.$toast.success(
                  response.data.success,
                  {
                      position:"top"
                  }
              )
              this.$inertia.visit(route('departments'));
          }).catch((error)=>{
            this.$toast.error(
              error.response.data.message,
                {
                    position:"top"
                }
            )
            this.$inertia.visit(route('departments'));
          })
        }
      },
      showImage(department){

        $vfm.show({
          component:ImageModal,
          bind:{
            id: department.id,
            imagePath: '/department/'+department.image,
            updateRoute: 'update.department',
            redirectRoute: 'departments'
          }
        })
      }
    }
  }
  </script>
  