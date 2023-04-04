<template>
    <vue-final-modal
        name="imageModal"
        v-slot="{ params, close }"
        v-bind="$attrs"
        classes="flex justify-center items-center"
        content-class="relative flex flex-col max-h-full mx-4 p-4 border dark:border-gray-800 rounded bg-white dark:bg-gray-900"
    >
        <span class="mr-8 text-2xl font-bold">
            <!-- <slot name="title">Image</slot> -->
        </span>

        <div class="flex-grow overflow-y-auto">
                <slot :params="params"></slot>
                <img :src="imagePath" alt="" width="500" height="500">
                <form @submit.prevent="submit" action="">
                    <input 
                        hidden
                        type="text"
                        v-model="formObj.id"
                    >
                    <input 
                        type="file" 
                        class=" mt-2 block w-full text-sm text-indigo-500 
                        file:mr-4 file:py-2 file:px-4
                        file:rounded-full file:border-0
                        file:text-sm file:font-semibold
                        file:bg-indigo-600 file:text-white
                        hover:file:bg-indigo-500
                        " 
                        @input="formObj.image = $event.target.files[0]"
                    >
                    <span v-if="error.image" class="text-red-600">Please select image</span>
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
        <button class="absolute top-0 right-0 mt-2 mr-2" @click="close">
            <mdi-close></mdi-close>
        </button>
    </vue-final-modal>
</template>
<script>
    import { $vfm } from "vue-final-modal";

    export default{
        props:['imagePath','updateRoute','redirectRoute','id'],
        data(){
            return{
                formObj:{
                    image:[],
                    id:this.id
                },
                error:{
                    image: false
                }
            }
        },

        methods:{
            submit(){
                const config = {
                    headers: { 'content-type': 'multipart/form-data' }
                }

                this.formObj.image == '' ? this.error.image = true : this.error.image = false
                if(this.error.image){
                    return false;
                }
                this.axios.post(route(this.updateRoute),this.formObj,config).then((response)=>{
                    $vfm.hide('imageModal');
                    this.$toast.success(
                        response.data.success,
                        {
                            position:"top"
                        }
                    )
                    this.$inertia.visit(route(this.redirectRoute));
                }).catch((errors)=>{
                    this.$toast.error(
                        errors.response.data.message,
                        {
                            position:"top"
                        }
                    )
                })

            }
        }
    }
</script>