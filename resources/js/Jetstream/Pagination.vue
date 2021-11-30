<template>
  <div
    v-if="links.length > 2"
    class="
      bg-white
      py-3
      flex
      items-center
      justify-between
      border-t border-gray-200
    "
  >
    <div class="flex-1 flex justify-between sm:hidden">
      <a
        href="#"
        class="
          relative
          inline-flex
          items-center
          px-4
          py-2
          border border-gray-300
          text-sm
          font-medium
          rounded-md
          text-gray-700
          bg-white
          hover:bg-gray-50
        "
      >
        Previous
      </a>
      <a
        href="#"
        class="
          ml-3
          relative
          inline-flex
          items-center
          px-4
          py-2
          border border-gray-300
          text-sm
          font-medium
          rounded-md
          text-gray-700
          bg-white
          hover:bg-gray-50
        "
      >
        Next
      </a>
    </div>
    <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
      <div>
        <p class="text-sm text-gray-700">
          Showing
          {{ " " }}
          <span class="font-medium">{{ from }}</span>
          {{ " " }}
          to
          {{ " " }}
          <span class="font-medium">{{ to }}</span>
          {{ " " }}
          of
          {{ " " }}
          <span class="font-medium">{{ total }}</span>
          {{ " " }}
          results
        </p>
      </div>
      <div>
        <nav
          class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px"
          aria-label="Pagination"
        >
          <template v-for="(link, key) in links" :key="key">
            <template v-if="key === 0">
              <div
                v-if="link.url === null"
                class="
                  relative
                  inline-flex
                  items-center
                  px-2
                  py-2
                  rounded-l-md
                  border border-gray-300
                  bg-white
                  text-sm
                  font-medium
                  text-gray-500
                  hover:bg-gray-50
                "
              >
                <span class="sr-only">Previous</span>
                <ChevronLeftIcon class="h-5 w-5" aria-hidden="true" />
              </div>
              <inertia-link
                v-else
                :href="link.url"
                class="
                  relative
                  inline-flex
                  items-center
                  px-2
                  py-2
                  rounded-l-md
                  border border-gray-300
                  bg-white
                  text-sm
                  font-medium
                  text-gray-500
                  hover:bg-gray-50
                "
              >
                <span class="sr-only">Previous</span>
                <ChevronLeftIcon class="h-5 w-5" aria-hidden="true" />
              </inertia-link>
            </template>
            <template v-if="key !== links.length - 1 && key !== 0">
              <span
                v-if="link.url === null"
                class="
                  bg-white
                  border-gray-300
                  text-gray-500
                  hover:bg-gray-50
                  relative
                  inline-flex
                  items-center
                  px-4
                  py-2
                  border
                  text-sm
                  font-medium
                "
                v-html="link.label"
              />
              <inertia-link
                v-else
                :href="link.url"
                v-html="link.label"
                class="
                  relative
                  inline-flex
                  items-center
                  px-4
                  py-2
                  border
                  text-sm
                  font-medium
                "
                :class="
                  link.active
                    ? 'z-10 bg-indigo-50 border-indigo-500 text-indigo-600'
                    : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50'
                "
              ></inertia-link>
            </template>

            <template v-if="key === links.length - 1">
              <div
                v-if="link.url === null"
                class="
                  relative
                  inline-flex
                  items-center
                  px-2
                  py-2
                  rounded-r-md
                  border border-gray-300
                  bg-white
                  text-sm
                  font-medium
                  text-gray-500
                  hover:bg-gray-50
                "
              >
                <span class="sr-only">Next</span>
                <ChevronRightIcon class="h-5 w-5" aria-hidden="true" />
              </div>
              <inertia-link
                v-else
                :href="link.url"
                class="
                  relative
                  inline-flex
                  items-center
                  px-2
                  py-2
                  rounded-r-md
                  border border-gray-300
                  bg-white
                  text-sm
                  font-medium
                  text-gray-500
                  hover:bg-gray-50
                "
              >
                <span class="sr-only">Next</span>
                <ChevronRightIcon class="h-5 w-5" aria-hidden="true" />
              </inertia-link>
            </template>
          </template>
        </nav>
      </div>
    </div>
  </div>
</template>

<script>
import { defineComponent } from "vue";
import { ChevronLeftIcon, ChevronRightIcon } from "@heroicons/vue/solid";
import { InertiaLink } from "@inertiajs/inertia-vue3";

export default defineComponent({
  components: {
    ChevronLeftIcon,
    ChevronRightIcon,
    InertiaLink,
  },
  props: ["links", "from", "to", "total"],
});
</script>
