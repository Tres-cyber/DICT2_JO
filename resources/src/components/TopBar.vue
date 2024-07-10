<template>
  <header
    class="mb-4 print:hidden"
    :class="admin ? 'bg-[#85070F]' : 'bg-sky-800'"
  >
    <div class="container mx-auto flex items-center p-2 sm:px-0">
      <a href="/" class="flex items-center">
        <img
          :src="'/assets/logo.png'"
          alt="DICT logo"
          class="h-12 w-12 rounded-full sm:h-16 sm:w-16"
        />
        <h1
          class="ml-4 font-serif text-xl font-semibold uppercase text-white sm:hidden"
          v-if="active"
        >
          {{ active }}
        </h1>

        <h1
          class="ml-4 hidden font-serif text-2xl font-semibold uppercase text-white sm:block"
        >
          {{ admin ? "Administrator" : "Job Order System" }}
        </h1>
      </a>

      <Transition
        enter-from-class="opacity-0"
        enter-active-class="transition duration-150"
        enter-to-class="opacity-100"
        leave-from-class="opacity-100"
        leave-active-class="transition duration-100"
        leave-to-class="opacity-0"
      >
        <div
          ref="sidebar"
          v-show="toggled"
          class="absolute inset-0 z-40 flex flex-col bg-black bg-opacity-20 md:hidden"
        ></div>
      </Transition>
      <Transition
        enter-from-class="translate-x-full"
        enter-active-class="transition-transform duration-150"
        enter-to-class="translate-x-0"
        leave-to-class="translate-x-full"
        leave-active-class="transition-transform duration-100"
        leave-from-class="translate-x-0"
      >
        <nav
          ref="sidebar"
          v-show="toggled"
          class="absolute right-0 top-0 z-50 flex h-screen flex-col items-center gap-4 md:hidden"
          :class="admin ? 'bg-[#85070F]' : 'bg-sky-800'"
        >
          <div class="my-8 flex items-center p-4">
            <img :src="'/assets/logo.png'" alt="" class="h-20 rounded-full" />
            <div class="flex flex-col items-center">
              <h1
                class="ml-4 font-serif text-xl font-semibold uppercase text-white"
              >
                Job Order System
              </h1>
              <h2 class="ml-4 font-serif font-medium text-white">
                {{ admin ? "Administrator" : "DICT Region II" }}
              </h2>
            </div>
          </div>
          <div v-if="admin" class="flex w-8/12 flex-col gap-6 [&_a]:uppercase">
            <slot>
              <a
                href="/admin/job_orders.php"
                class="font-serif font-semibold tracking-wide text-neutral-200 hover:text-white"
                :class="[active == 'activities' && '!text-white']"
              >
                Job Orders
              </a>
              <a
                href="/admin/activities.php"
                class="font-serif font-semibold tracking-wide text-neutral-200 hover:text-white"
                :class="[active == 'activities' && '!text-white']"
              >
                Activities
              </a>
              <a
                href="/admin/accounts.php"
                class="font-serif font-semibold tracking-wide text-neutral-200 hover:text-white"
                :class="[active == 'accounts' && '!text-white']"
              >
                Accounts
              </a>
              <a
                href="/admin/projects.php"
                class="font-serif font-semibold tracking-wide text-neutral-200 hover:text-white"
                :class="[active == 'projects' && '!text-white']"
              >
                Projects
              </a>
              <a
                href="/admin/personnels.php"
                class="font-serif font-semibold tracking-wide text-neutral-200 hover:text-white"
                :class="[active == 'projects' && '!text-white']"
              >
                Personnels
              </a>
            </slot>
          </div>
          <a
            href="/logout.php"
            class="flex w-8/12 justify-center rounded bg-white px-4 py-2"
            :class="[admin && 'my-4']"
          >
            <span
              class="font-serif font-bold uppercase"
              :class="admin ? 'text-[#85070F]' : 'text-sky-800'"
            >
              Logout
            </span>
          </a>
        </nav>
      </Transition>
      <nav ref="sidebar" class="ml-auto hidden items-center gap-4 md:flex">
        <slot v-if="admin">
          <a
                href="/admin/job_orders.php"
                class="font-serif font-semibold tracking-wide text-neutral-200 hover:text-white"
                :class="[active == 'activities' && '!text-white']"
              >
                Job Orders
              </a>
          <a
            href="/admin/activities.php"
            class="font-serif font-semibold tracking-wide text-neutral-200 hover:text-white"
            :class="[active == 'activities' && '!text-white']"
          >
            Activities
          </a>
          <a
            href="/admin/accounts.php"
            class="font-serif font-semibold tracking-wide text-neutral-200 hover:text-white"
            :class="[active == 'accounts' && '!text-white']"
          >
            Accounts
          </a>
          <a
            href="/admin/projects.php"
            class="font-serif font-semibold tracking-wide text-neutral-200 hover:text-white"
            :class="[active == 'projects' && '!text-white']"
          >
            Projects
          </a>
          <a
                href="/admin/personnels.php"
                class="font-serif font-semibold tracking-wide text-neutral-200 hover:text-white"
                :class="[active == 'projects' && '!text-white']"
              >
                Personnels
              </a>
        </slot>
        <a
          href="/logout.php"
          class="ml-4 flex justify-center rounded border border-white px-4 py-2"
        >
          <span class="font-serif text-sm font-bold uppercase text-white">
            Logout
          </span>
        </a>
      </nav>
      <button class="btn ml-auto md:hidden" @click="toggled = true">
        <font-awesome-icon icon="fa-solid fa-bars" class="h-8 text-white" />
      </button>
    </div>
  </header>
</template>
<script setup lang="ts">
import {
  useBreakpoints,
  breakpointsTailwind,
  onClickOutside,
} from "@vueuse/core";
import { watch, ref, Transition } from "vue";
const breakpoints = useBreakpoints(breakpointsTailwind);
const isTablet = breakpoints.smaller("md");

const sidebar = ref<HTMLElement | null>(null);
const toggled = ref(false);

withDefaults(
  defineProps<{
    admin?: boolean;
    active?: string;
  }>(),
  { admin: false },
);

const isShrinking = ref(false);

onClickOutside(sidebar, () => {
  toggled.value = false;
});

watch([isTablet, toggled], ([isTablet, toggled]) => {
  if (toggled && isTablet) {
    document.body.style.position = "fixed";
    document.body.style.width = "100%";
    document.body.style.height = "100%";
  } else {
    document.body.style.position = "";
    document.body.style.width = "";
    document.body.style.height = "";
  }
});

watch(isTablet, (isTablet) => {
  if (!isTablet) toggled.value = false;
});

watch(isTablet, (isTablet) => {
  if (!isTablet) {
    isShrinking.value = true;
  } else {
    setTimeout(() => {
      isShrinking.value = false;
    }, 200);
  }
});
</script>
