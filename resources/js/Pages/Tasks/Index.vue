<template>
  <div class="flex h-screen bg-gray-200 font-roboto">
    <Sidebar />

    <div class="flex-1 flex flex-col overflow-hidden">
      <Header :title="title" />

      <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-200">
        <div class="container mx-auto px-6 py-8">
          
          <div class="mt-4">
            <button
              @click="getTasks(user_id,'completed')"
              class="
                px-4
                py-2
                
                text-white
                bg-green-600
                rounded-md
                hover:bg-green-500
                focus:outline-none focus:bg-black
                float-left
              "
            >
              Completed Tasks
            </button>
            <button
            @click="getTasks(user_id,'pending')"
              class="
                  px-4
                  py-2
                  ml-2
                  text-white
                  bg-yellow-600
                  rounded-md
                  hover:bg-yellow-500
                  focus:outline-none focus:bg-black
                  float-left
              "
            >
              Pending Tasks
            </button>
            
            <button
            @click="getTasks(user_id,'denied')"
              class="
                px-4
                py-2
                ml-2
                text-white
                bg-red-600
                rounded-md
                hover:bg-red-500
                focus:outline-none focus:bg-black
                float-left
              "
            >
              Denied Tasks
            </button>
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
              v-if="$page.props.userRole == 'Super Admin' || $page.props.userRole == 'Admin'"
              @click="addTask(user_id)"
            >
              Assign Task</button
            ><br />
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
                        Title
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
                        Status
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
                        Due Date
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
                      v-for="(task, index) in tasks.data"
                      :key="index"
                      class="hover:bg-gray-200"
                    >
                      <td class="px-6 py-4 text-lg text-gray-700 border-b">
                        <img
                          :src="'/tasks/' + task.image"
                          class="object-none h-20 w-20 border-radius: 3rem;"
                          style="border-radius: 40px"
                          alt=""
                        />
                      </td>
                      <td class="px-6 py-4 text-lg text-gray-700 border-b">
                        {{ task.title }}
                      </td>
                      <td class="px-6 py-4 text-gray-500 border-b">
                        {{ task.status }}
                      </td>
                      <td class="px-6 py-4 text-gray-500 border-b">
                        {{ task.due_date }}
                      </td>
                      <td class="px-6 py-4 text-gray-500 border-b">
                        {{ task.user.department.name }}
                      </td>
                      <td class="px-6 py-4 text-gray-500 border-b">
                        <button
                          @click="viewTask(task)"
                          class="
                            px-4
                            py-2
                            text-gray-200
                            bg-indigo-800
                            rounded-md
                            hover:bg-indigo-500
                            focus:outline-none focus:bg-gray-700
                          "
                        >
                          View
                        </button>
                        <button
                          @click="editTask(task)"
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
                          v-if="$page.props.userRole == 'Super Admin' || $page.props.userRole == 'Admin'"
                        >
                          Edit
                        </button>
                        <button
                          @click="deleteTask(task.id,task.user.id)"
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
                          v-if="$page.props.userRole == 'Super Admin' || $page.props.userRole == 'Admin'"
                        >
                          Delete
                        </button>
                      </td>
                    </tr>
                  </tbody>
                </table>
                <Pagination
                  :links="tasks.links"
                  :count="tasks.total"
                  :totalPages="tasks.last_page"
                />
              </div>
            </div>
          </div>
          <slot />
        </div>
      </main>
    </div>
  </div>
  <ModalsContainer />
</template>
  
  <script>
import Header from "../Components/Header.vue";
import Sidebar from "../Components/Sidebar.vue";
import addTaskModal from "../Modals/addTaskModal.vue";
import EditTaskModal from "../Modals/EditTaskModal.vue";
import ViewTaskModal from "../Modals/ViewTaskModal.vue";
import { $vfm } from "vue-final-modal";
import Pagination from "../Components/Pagination.vue";

export default {
  props: ["departments", "tasks", "user_id"],
  data() {
    return {
      title: "Tasks",
      employes: [],
      tasks:this.tasks
    };
  },
  components: {
    Header,
    Sidebar,
    Pagination,
    ViewTaskModal
  },
  methods: {
    addTask(user_id) {
      $vfm.show({
        component: addTaskModal,
        bind: {
          departments: this.departments,
          user_id: user_id,
        },
      });
    },

    editTask(task) {
      $vfm.show({
        component: EditTaskModal,
        bind: {
          task: task,
        },
      });
    },

    viewTask(task) {
      $vfm.show({
        component: ViewTaskModal,
        bind: {
          task: task,
        },
      });
    },

    deleteTask(task_id,user_id){
      if (confirm("Do you want to delete this task ?")) {
        this.axios.get(route('delete.task',task_id))
        .then((response)=>{
          this.$toast.success(response.data.success,{
            position: "top"
          })
          this.$inertia.visit(route('tasks',user_id))
        })
      }
    },

    getTasks(user_id,status){
      this.axios.post(route('get.tasks'),{
        status: status,
        user_id: user_id
      }).then((response)=>{
        console.log(response.data.tasks.data);
        this.tasks = response.data.tasks
      })
    }
  },
};
</script>
  