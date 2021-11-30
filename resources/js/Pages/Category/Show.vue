<template>
  <app-layout title="Manage Categories">
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Manage Categories
      </h2>
    </template>

    <card>
      <template #header>
        <Link :href="route('categories.create')">
          <jet-button :type="'button'">Create New</jet-button>
        </Link>
        <div class="w-1/2">
          <jet-input
            id="categorySearch"
            placeholder="Search..."
            type="text"
            class="mt-1 block w-full"
            v-model="searchQuery"
            autocomplete="categorySearch"
          />
        </div>
      </template>
      <template #content>
        <jet-table :columnNames="this.columnNames">
          <template v-if="categories.data.length > 0">
            <tr v-for="(category, key) in categories.data" :key="key">
              <jet-td>
                <div class="flex items-center">
                  <div class="flex-shrink-0 h-10 w-10">
                    <img
                      class="h-10 w-10 rounded-sm"
                      :src="category.cover_image"
                      :alt="category.name"
                    />
                  </div>
                </div>
              </jet-td>

              <jet-td>
                <div class="text-sm text-gray-900">{{ category.name }}</div>
              </jet-td>

              <jet-td>
                <div class="text-sm text-gray-900">{{ category.slug }}</div>
              </jet-td>

              <jet-td>
                <span
                  class="
                    px-2
                    inline-flex
                    text-xs
                    leading-5
                    font-semibold
                    rounded-full
                    bg-green-100
                    text-green-800
                  "
                >
                  150
                </span>
              </jet-td>

              <jet-td class="text-right font-medium">
                <Link :href="route('categories.edit', { id: category.id })"
                  ><jet-button class="mr-4">Edit</jet-button></Link
                >
                <jet-button @click="categoryIdBeginDeleted = category.id"
                  >Delete</jet-button
                >
              </jet-td>
            </tr>
          </template>
        </jet-table>

        <pagination
          v-if="categories.data.length > 0"
          :from="categories.from"
          :to="categories.to"
          :total="categories.total"
          :links="categories.links"
        ></pagination>
      </template>
    </card>
  </app-layout>
  <jet-confirmation-modal
    :show="categoryIdBeginDeleted"
    @close="categoryIdBeginDeleted = null"
  >
    <template #title> Delete Category </template>

    <template #content>
      Are you sure you would like to delete this category?
    </template>

    <template #footer>
      <jet-secondary-button @click="categoryIdBeginDeleted = null">
        Cancel
      </jet-secondary-button>

      <jet-danger-button
        class="ml-2"
        @click="deleteCategory"
        :class="{ 'opacity-25': deleteCategoryForm.processing }"
        :disabled="deleteCategoryForm.processing"
      >
        Delete
      </jet-danger-button>
    </template>
  </jet-confirmation-modal>
</template>

<script>
import { defineComponent } from "vue";
import AppLayout from "@/Layouts/AppLayout.vue";
import JetButton from "@/Jetstream/Button.vue";
import Pagination from "@/Jetstream/Pagination.vue";
import JetFormSection from "@/Jetstream/FormSection.vue";
import JetInput from "@/Jetstream/Input.vue";
import JetInputError from "@/Jetstream/InputError.vue";
import JetLabel from "@/Jetstream/Label.vue";
import JetActionMessage from "@/Jetstream/ActionMessage.vue";
import JetSecondaryButton from "@/Jetstream/SecondaryButton.vue";
import Card from "@/Jetstream/Card.vue";
import JetTable from "@/Jetstream/Table.vue";
import JetTd from "@/Jetstream/Td.vue";
import { Link } from "@inertiajs/inertia-vue3";
import { Inertia } from "@inertiajs/inertia";
import JetDangerButton from "@/Jetstream/DangerButton.vue";
import JetConfirmationModal from "@/Jetstream/ConfirmationModal.vue";

export default defineComponent({
  components: {
    Pagination,
    JetTable,
    JetTd,
    Card,
    AppLayout,
    JetActionMessage,
    JetButton,
    JetFormSection,
    JetInput,
    JetInputError,
    JetLabel,
    JetSecondaryButton,
    Link,
    JetConfirmationModal,
    JetDangerButton,
  },

  props: ["categories", "filters", "create_url"],

  data() {
    return {
      form: this.$inertia.form({
        _method: "POST",
        name: null,
      }),
      searchQuery: this.filters.search,
      columnNames: ["Image", "Name", "Slug", "Episodes", ""],
      deleteCategoryForm: this.$inertia.form(),
      categoryIdBeginDeleted: null,
    };
  },

  watch: {
    searchQuery(val, oldVal) {
      this.getAllRecords(val);
    },
  },

  methods: {
    getAllRecords: _.debounce((val) => {
      Inertia.get(
        route("categories.show"),
        { search: val },
        { preserveState: true }
      );
    }, 200),
    deleteCategory() {
      this.deleteCategoryForm.delete(
        route("categories.delete", this.categoryIdBeginDeleted),
        {
          preserveScroll: true,
          preserveState: true,
          onSuccess: () => (this.categoryIdBeginDeleted = null),
        }
      );
    },
  },
});
</script>
