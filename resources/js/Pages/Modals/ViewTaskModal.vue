<template>
  <vue-final-modal
    name="taskModal"
    :max-width="100"
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
      <img :src="'/tasks/' + task.image" alt="" style="width: 700px" />
      <div class="flex flex-between">
        <h2 style="font-size: 30px" class="text-green-600">
          {{ task.title.toUpperCase() }}
        </h2>
        <select
          name=""
          id=""
          v-model="formObj.status"
          @change="changeStatus(task.id)"
          class="float-right mt-1"
          style="border-radius: 30px; margin-left: 450px"
        >
          <option value="" selected disabled>Select status</option>
          <option value="pending">Pending</option>
          <option value="completed">Completed</option>
          <option value="denied">Denied</option></select
        ><br /><br />
      </div>
      <span style="font-size: 20px" class="float-right"
        >Due Date: <span class="text-red-600">{{ task.due_date }}</span></span
      >
      <span style="font-size: 20px">Description:</span>
      <p>{{ task.description }}</p>

      <!-- <p>{{ task.description }}</p> -->
    </div>
    <button class="absolute top-0 right-0 mt-2 mr-2" @click="close">
      <mdi-close></mdi-close>
    </button>
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
      },
    };
  },

  methods: {
    changeStatus(id) {
      this.axios
        .post(route("change.status", id), this.formObj)
        .then((response) => {
          $vfm.hide("taskModal");
          this.$toast.success(response.data.success, {
            position: "top",
          });
          this.$inertia.visit(route("tasks", this.task.user.id));
        });
    },
  },
};
</script>