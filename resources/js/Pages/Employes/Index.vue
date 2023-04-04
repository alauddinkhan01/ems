<template>
  <div class="flex h-screen bg-gray-200 font-roboto">
    <Sidebar />

    <div class="flex-1 flex flex-col overflow-hidden">
      <Header :title="title" />

      <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-200">
        <div class="container mx-auto px-6 py-8">
          <div class="mt-4">
            <button
              class="
                px-4
                py-2
                text-gray-200
                bg-indigo-800
                rounded-md
                hover:bg-indigo-500
                focus:outline-none focus:bg-gray-700
                float-right
              "
              @click="addEmploye"
            >
              Add Employe</button
            ><br /><br>
            <input type="text" placeholder="Search..." v-model="searchEmploye" class="float-right"  style="border-radius:60px; border-color: blue;"><br>
            <div class="mt-6">
              <div class="my-6 overflow-hidden bg-white rounded-md shadow">
                <table class="w-full text-left border-collapse">
                  <thead class="border-b">
                    <tr>
                      <th
                        class="
                          px-5
                          py-3
                          text-sm
                          font-medium
                          text-gray-100
                          uppercase
                          bg-indigo-800
                        "
                      >
                        Image
                      </th>
                      <th
                        class="
                          px-5
                          py-3
                          text-sm
                          font-medium
                          text-gray-100
                          uppercase
                          bg-indigo-800
                        "
                      >
                        Name
                      </th>
                      <th
                        class="
                          px-5
                          py-3
                          text-sm
                          font-medium
                          text-gray-100
                          uppercase
                          bg-indigo-800
                        "
                      >
                        Email
                      </th>
                      <th
                        class="
                          px-5
                          py-3
                          text-sm
                          font-medium
                          text-gray-100
                          uppercase
                          bg-indigo-800
                        "
                      >
                        Phone
                      </th>
                      <th
                        class="
                          px-5
                          py-3
                          text-sm
                          font-medium
                          text-gray-100
                          uppercase
                          bg-indigo-800
                        "
                      >
                        Department
                      </th>
                      <th
                        class="
                          px-5
                          py-3
                          text-sm
                          font-medium
                          text-gray-100
                          uppercase
                          bg-indigo-800
                        "
                      >
                        Action
                      </th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr
                      v-for="(employe, index) in employes.data"
                      :key="employe.id"
                      class="hover:bg-gray-200"
                    >
                      <td class="px-6 py-4 text-lg text-gray-700 border-b">
                        <img
                          :src="'/users/' + employe.image"
                          class="object-none h-20 w-20 border-radius: 3rem;"
                          style="border-radius: 40px"
                          alt=""
                        />
                      </td>
                      <td class="px-6 py-4 text-lg text-gray-700 border-b">
                        {{ employe.name }}
                      </td>
                      <td class="px-6 py-4 text-gray-500 border-b">
                        {{ employe.email }}
                      </td>
                      <td class="px-6 py-4 text-gray-500 border-b">
                        {{ employe.mobile }}
                      </td>
                      <td class="px-6 py-4 text-gray-500 border-b">
                        {{ employe.department.name }}
                      </td>
                      <td class="px-6 py-4 text-gray-500 border-b flex flex-between">
                        <Link
                          :href="route('tasks',employe.id)"
                          class="
                            px-4
                            py-2
                            text-gray-200
                            bg-green-800
                            rounded-md
                            hover:bg-green-500
                            focus:outline-none focus:bg-gray-700
                          "
                        >
                          Tasks
                        </Link>
                        <button
                          @click="editEmploye(employe)"
                          class="
                            px-4
                            py-2
                            ml-2                           
                            text-gray-200
                            bg-indigo-800
                            rounded-md
                            hover:bg-indigo-500
                            focus:outline-none focus:bg-gray-700
                          "
                        >
                          Edit
                        </button>
                        <button
                          @click="deleteEmploye(employe.id)"
                          class="
                            px-4
                            ml-2
                            py-2
                            text-red-200
                            bg-red-700
                            rounded-md
                            hover:bg-red-500
                            focus:outline-none focus:bg-red-700
                          "
                        >
                          Delete
                        </button>
                      </td>
                    </tr>
                  </tbody>
                </table>
                <Pagination
                  :links="employes.links"
                  :count="employes.total"
                  :totalPages="employes.last_page"
                />
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
import Pagination from "../Components/Pagination.vue";

import AddEmployeModal from "../Modals/AddEmployeModal.vue";
import EditEmployeModal from "../Modals/EditEmployeModal.vue";
import { $vfm } from "vue-final-modal";
import { Link } from "@inertiajs/inertia-vue3";
import debounce from 'lodash/debounce';

export default {
  props: ["roles", "employes", "departments",'searchedEmploye'],
  data() {
    return {
      title: "Employes",
      searchEmploye:this.searchedEmploye
    };
  },
  components: {
    Header,
    Sidebar,
    AddEmployeModal,
    EditEmployeModal,
    Pagination,
    Link
},
  watch:{
    searchEmploye(value){
      this.findEmploye(value);
    }
  },
  methods: {

    findEmploye: debounce(function(value) {
      this.$inertia.get(route('employes'), {search: value}, {
        preserveState: true,
        replace: true
      });
    }, 350),

    addEmploye() {
      $vfm.show({
        component: AddEmployeModal,
        bind: {
          roles: this.roles,
          departments: this.departments,
        },
      });
    },

    editEmploye(employe) {
      $vfm.show({
        component: EditEmployeModal,
        bind: {
          roles: this.roles,
          employe: employe,
          departments: this.departments,
        },
      });
    },

    deleteEmploye(id) {
      if (confirm("Do you really want to delete this Employe?")) {
        this.axios
          .delete(route("delete.employe", id))
          .then((response) => {
            this.$toast.success(response.data.success, {
              position: "top",
            });
            this.$inertia.visit(route("employes"));
          })
          .catch((error) => {
            this.$toast.error(error.response.data.message, {
              position: "top",
            });
            this.$inertia.visit(route("employes"));
          });
      }
    },
  },
};
</script>
  