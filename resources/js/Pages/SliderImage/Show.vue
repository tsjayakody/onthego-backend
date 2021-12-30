<template>
  <app-layout title="Manage Slider Images">
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Manage Slider Images
      </h2>
    </template>
    <card>
      <template #header>
        <Link :href="route('sliderImages.create')">
          <jet-button :type="'button'">Create New</jet-button>
        </Link>
        <div class="w-1/2 sr-only">
          <jet-input
            id="searchInput"
            placeholder="Search..."
            type="text"
            class="mt-1 block w-full"
            autocomplete="search"
          />
        </div>
      </template>
      <template #content>
        <jet-table :columnNames="this.columnNames">
          <template v-if="images.length > 0">
            <tr v-for="(image, key) in images" :key="key">
              <!-- slider image image -->
              <jet-td>
                <div class="flex items-center">
                  <div class="flex-shrink-0 h-10 w-10">
                    <img
                      class="h-10 w-10 rounded-sm"
                      :src="image.slider_image_thumb"
                      :alt="image.title"
                    />
                  </div>
                </div>
              </jet-td>
              <!-- slider title -->
              <jet-td>
                <div class="text-sm text-gray-900 whitespace-pre-wrap">
                  {{ image.title }}
                </div>
              </jet-td>

              <!-- slider description -->
              <jet-td>
                <div class="text-sm text-gray-900 whitespace-pre-wrap">
                  {{ image.description }}
                </div>
              </jet-td>

              <!-- display order -->
              <jet-td>
                <jet-button @click="changeOrder(image.id, 'up')">
                  &#8593;
                </jet-button>
                <jet-button class="ml-1" @click="changeOrder(image.id, 'down')">
                  &#8595;
                </jet-button>
              </jet-td>

              <!-- hoster action buttons -->
              <jet-td class="text-right font-medium">
                <!-- <jet-button class="mr-4">View</jet-button> -->
                <Link :href="route('sliderImages.edit', { id: image.id })">
                  <jet-button class="mr-1">Edit</jet-button>
                </Link>
                <jet-button @click="imageIdBeginDeleted = image.id">
                  Delete
                </jet-button>
              </jet-td>
            </tr>
          </template>
        </jet-table>
      </template>
    </card>
  </app-layout>
  <jet-confirmation-modal
    :show="imageIdBeginDeleted"
    @close="imageIdBeginDeleted = null"
  >
    <template #title> Delete Slider Image </template>

    <template #content>
      Are you sure you would like to delete this image?
    </template>

    <template #footer>
      <jet-secondary-button @click="imageIdBeginDeleted = null">
        Cancel
      </jet-secondary-button>

      <jet-danger-button
        class="ml-2"
        @click="deleteImage"
        :class="{ 'opacity-25': deleteImageForm.processing }"
        :disabled="deleteImageForm.processing"
      >
        Delete
      </jet-danger-button>
    </template>
  </jet-confirmation-modal>
</template>

<script>
import { defineComponent } from "vue";
import { Link } from "@inertiajs/inertia-vue3";
import Card from "@/Jetstream/Card.vue";
import AppLayout from "@/Layouts/AppLayout.vue";
import JetInput from "@/Jetstream/Input.vue";
import JetButton from "@/Jetstream/Button.vue";
import JetTable from "@/Jetstream/Table.vue";
import JetTd from "@/Jetstream/Td.vue";
import Pagination from "@/Jetstream/Pagination.vue";
import JetDangerButton from "@/Jetstream/DangerButton.vue";
import JetConfirmationModal from "@/Jetstream/ConfirmationModal.vue";
import JetActionMessage from "@/Jetstream/ActionMessage.vue";
import JetSecondaryButton from "@/Jetstream/SecondaryButton.vue";
import { Inertia } from "@inertiajs/inertia";

export default defineComponent({
  props: ["images"],
  components: {
    AppLayout,
    Card,
    JetInput,
    JetButton,
    Link,
    JetTable,
    JetTd,
    Pagination,
    JetConfirmationModal,
    JetDangerButton,
    JetActionMessage,
    JetSecondaryButton,
    Inertia,
  },

  data() {
    return {
      columnNames: ["Image", "Title", "Description", "Display Order", ""],
      imageIdBeginDeleted: null,
      deleteImageForm: this.$inertia.form(),
    };
  },
  methods: {
    changeOrder(imageId, orderDirection) {
      this.$inertia
        .form({
          id: imageId,
          direction: orderDirection,
        })
        .post(route("sliderImages.changeOrder"));
    },
    deleteImage() {
      this.deleteImageForm.delete(
        route("sliderImages.delete", this.imageIdBeginDeleted),
        {
          preserveScroll: true,
          preserveState: true,
          onSuccess: () => (this.imageIdBeginDeleted = null),
        }
      );
    },
  },
});
</script>

