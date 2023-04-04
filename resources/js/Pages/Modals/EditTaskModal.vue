<template>
  <vue-final-modal
    name="EditTaskModal"
    v-slot="{ params, close }"
    v-bind="$attrs"
    classes="flex justify-center items-center"
    content-class="relative flex flex-col max-h-full mx-4 p-4 border dark:border-gray-800 rounded bg-white dark:bg-gray-900"
  >
    <span class="mr-8 text-2xl font-bold">
      <slot name="title">Edit Task</slot>
    </span>
    <div class="flex-grow overflow-y-auto mt-4">
      <slot :params="params"></slot>
      <form @submit.prevent="submit" enctype="multipart/form-data">
        <div class="cols-12">
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
            v-model="formObj.task_id"
            hidden
          />
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
            <label class="text-gray-700" for="datetime">Status</label>
            <select
                 name="" id=""
                 v-model="formObj.status"
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
            >
                <option value="" selected disabled> Select Status</option>
                <option value="pending">Pending</option>
                <option value="completed">Completed</option>
                <option value="denied">Denied</option>
            </select>
            <span v-if="error.due_date" class="text-red-600"
              >Please select status</span
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
          </div>
          <div>
            <img
              :src="'/tasks/' + task.image"
              width="250"
              height="250"
              alt=""
            />
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
  props: ["task"],
  data() {
    return {
      formObj: {
        status: this.task.status,
        task_id: this.task.id,
        title: this.task.title,
        description: this.task.description,
        due_date: this.task.due_date,
        image: [],
      },
      error: {
        title: false,
        description: false,
        due_date: false,
      },
    };
  },
  methods: {
    submit() {
      console.log(this.formObj.title);
      this.formObj.title == ""
        ? (this.error.title = true)
        : (this.error.title = false);
      this.formObj.description == ""
        ? (this.error.description = true)
        : (this.error.description = false);
      this.formObj.due_date == ""
        ? (this.error.due_date = true)
        : (this.error.due_date = false);
      if (this.error.title || this.error.description || this.due_date) {
        return false;
      }
      const config = {
        headers: { "content-type": "multipart/form-data" },
      };
      this.axios
        .post(route("update.task"), this.formObj, config)
        .then((response) => {
          $vfm.hide("EditTaskModal");
          this.$toast.success(response.data.success, {
            position: "top",
          });
          this.$inertia.visit(route("tasks", this.task.user.id));
        });
    },
  },
};
</script>
  
  <style>
</style>