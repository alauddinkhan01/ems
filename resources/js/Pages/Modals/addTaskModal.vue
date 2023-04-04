<template>
  <vue-final-modal
    name="addTaskModal"
    v-slot="{ params, close }"
    v-bind="$attrs"
    classes="flex justify-center items-center"
    content-class="relative flex flex-col max-h-full mx-4 p-4 border dark:border-gray-800 rounded bg-white dark:bg-gray-900"
  >
    <span class="mr-8 text-2xl font-bold">
      <slot name="title">Assign Task</slot>
    </span>
    <div class="flex-grow overflow-y-auto mt-4">
      <slot :params="params"></slot>
      <form @submit.prevent="submit" enctype="multipart/form-data">
        <div class="cols-12">
            <input
            class="
              w-full
              mt-2
              border-gray-200
              rounded-md
              focus:border-indigo-600
              focus:ring
              focus:ring-opacity-40
              focus:ring-indigo-500
            "
            hidden
            type="text"
            id="title"
            v-model="formObj.employe_id"
          />
          <label class="text-gray-700" for="title">Title</label>
          <input
            class="
              w-full
              mt-2
              border-gray-200
              rounded-md
              focus:border-indigo-600
              focus:ring
              focus:ring-opacity-40
              focus:ring-indigo-500
            "
            type="text"
            id="title"
            v-model="formObj.title"
          />
          <span v-if="error.title" class="text-red-600"
            >Please enter Title</span
          >
        </div>
        <div class="cols-12 mt-4">
          <label class="text-gray-700" for="description">Description</label>
          <textarea
            name=""
            id="description"
            cols="30"
            rows="5"
            class="
              w-full
              mt-2
              border-gray-200
              rounded-md
              focus:border-indigo-600
              focus:ring
              focus:ring-opacity-40
              focus:ring-indigo-500
            "
            v-model="formObj.description"
          >
          </textarea>
          <span v-if="error.description" class="text-red-600"
            >Please enter Description</span
          >
        </div>
        <div class="grid grid-cols-1 gap-6 mt-4 sm:grid-cols-2">
          <div>
            <label class="text-gray-700" for="datetime">Due Date</label>
            <input
              class="
                w-full
                mt-2
                border-gray-200
                rounded-md
                focus:border-indigo-600
                focus:ring
                focus:ring-opacity-40
                focus:ring-indigo-500
              "
              type="datetime-local"
              id="datetime"
              v-model="formObj.due_date"
            />
            <span v-if="error.due_date" class="text-red-600"
              >Please select due date</span
            >
          </div>
          <div>
            <label class="text-gray-700" for="passwordConfirmation"
              >Image</label
            >
            <input
              class="
                mt-2
                block
                w-full
                text-sm text-indigo-500
                file:mr-4
                file:py-2
                file:px-4
                file:rounded-full
                file:border-0
                file:text-sm
                file:font-semibold
                file:bg-indigo-600
                file:text-white
                hover:file:bg-indigo-500
              "
              type="file"
              @input="formObj.image = $event.target.files[0]"
            />
            <!-- <span v-if="error.image" class="text-red-600">Please select image</span> -->
          </div>
        </div>
        <div class="flex justify-end mt-4">
          <button
            class="
              px-4
              py-2
              text-gray-200
              bg-indigo-800
              rounded-md
              hover:bg-indigo-600
              focus:outline-none focus:bg-indigo-700
            "
            type="submit"
          >
            Save
          </button>
        </div>
      </form>
    </div>
  </vue-final-modal>
</template>

<script>
import { $vfm } from "vue-final-modal";
export default {
  props: ["departments","user_id"],
  data() {
    return {
      formObj: {
        employe_id: this.user_id,
        title: null,
        description: null,
        due_date: null,
        image: [],
      },
      error: {
        employe_id: false,
        title: false,
        description: false,
        due_date: false,
      },
    };
  },
  methods: {
    getEmployes() {
      this.axios
        .get(route("department.employes", this.department_id))
        .then((response) => {
          this.employes = response.data.employes;
          this.showEmployes = true;
          this.error.employe_id = false;
        });
    },

    submit() {
      this.formObj.title == null
        ? (this.error.title = true)
        : (this.error.title = false);
      this.formObj.description == null
        ? (this.error.description = true)
        : (this.error.description = false);
      this.formObj.employe_id == null
        ? (this.error.employe_id = true)
        : (this.error.employe_id = false);
      this.formObj.due_date == null
        ? (this.error.due_date = true)
        : (this.error.due_date = false);
      if (
        this.error.title ||
        this.error.description ||
        this.error.employe_id ||
        this.due_date
      ) {
        return false;
      }
      const config = {
        headers: { "content-type": "multipart/form-data" },
      };
      this.axios
        .post(route("assign.task"), this.formObj, config)
        .then((response) => {
          $vfm.hide("addTaskModal");
          this.$toast.success(response.data.success, {
            position: "top",
          });
          this.$inertia.visit(route("tasks",this.formObj.employe_id));
        });
    },
  },
};
</script>

<style>
</style>