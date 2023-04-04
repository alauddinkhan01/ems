<template>
  <div class="flex" v-if="isOpen">
    <!-- Backdrop -->
    <div
      class="
        fixed
        inset-0
        z-20
        transition-opacity
        bg-black
        opacity-50
        lg:hidden
      "
    ></div>
    <!-- End Backdrop -->

    <div
      class="
        fixed
        inset-y-0
        left-0
        z-30
        w-64
        overflow-y-auto
        transition
        duration-300
        transform
        bg-indigo-800
        lg:translate-x-0 lg:static lg:inset-0
      "
    >
      <div class="flex items-center justify-center mt-8">
        <div class="flex items-center">
          <i class="fa fa-home text-white" style="font-size: 25px"></i>

          <span class="mx-2 text-2xl font-semibold text-white">EMS</span>
        </div>
      </div>

      <nav class="mt-10">
        <Link
          v-if="
            $page.props.userRole == 'Super Admin' ||
            $page.props.userRole == 'Admin'
          "
          class="flex text-white items-center px-6 py-2 mt-4 duration-200"
          :class="
            $page.component == 'NewDashboard' ? 'border-l-4 text-gray-400' : ''
          "
          :href="route('dashboard')"
        >
          <i class="fa fa-dashboard" style="font-size: 25px"></i>

          <span class="mx-4">Dashboard</span>
        </Link>

        <Link
          v-if="
            $page.props.userRole == 'Super Admin' ||
            $page.props.userRole == 'Admin'
          "
          class="flex items-center px-6 py-2 mt-4 duration-200 text-white"
          :class="
            $page.component == 'Departments/Index' ||
            $page.component == 'Departments/Create'
              ? ' border-l-4 text-gray-400'
              : ''
          "
          :href="route('departments')"
        >
          <i class="fa fa-building" style="font-size: 25px"></i>

          <span class="mx-4">Departments</span>
        </Link>
        <Link
          v-if="
            $page.props.userRole == 'Super Admin' ||
            $page.props.userRole == 'Admin'
          "
          class="flex items-center px-6 py-2 mt-4 duration-200 text-white"
          :class="
            $page.component == 'Employes/Index' ||
            $page.component == 'Employes/Create' ||
            $page.component == 'Tasks/Index'
              ? ' border-l-4'
              : ''
          "
          :href="route('employes')"
        >
          <i class="fa fa-users" style="font-size: 25px"></i>

          <span class="mx-4">Employes</span>
        </Link>
        <Link v-if="$page.props.userRole == 'Employe'"
          class="flex items-center px-6 py-2 mt-4 duration-200 text-white"
          :class="$page.component == 'Tasks/Index'? 
          ' border-l-4' : ''"
          :href="route('my.tasks')"
        >
          
          <i class="fa fa-tasks " style="font-size:25px"></i>

          <span class="mx-4">My Tasks </span>
        </Link>
      </nav>
    </div>
  </div>
</template>

<script>
import bus from "vue3-eventbus";

export default {
  data() {
    return {
      isOpen: true,
    };
  },
  created() {
    bus.on("togglesidebar", (isOpen) => {
      this.isOpen = isOpen;
    });
  },
};
</script>
