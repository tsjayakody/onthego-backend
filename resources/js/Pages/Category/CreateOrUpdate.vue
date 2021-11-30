<template>
  <app-layout title="Manage Categories">
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Manage Categories
      </h2>
    </template>

    <div>
      <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
        <jet-form-section @submitted="createOrUpdateCategory">
          <template #title>
            {{
              category == undefined ? "Create new category" : "Edit category"
            }}
          </template>

          <template #description>
            <ul>
              <li>• Create or Update the category.</li>
              <li>• Image accept ratio should be 1:1.</li>
              <li>• Image formt should be .png, .jpg or .jpeg</li>
            </ul>
          </template>

          <template #form>
            <!-- Name -->
            <div class="col-span-6 sm:col-span-4">
              <jet-label for="name" value="Name" />
              <jet-input
                id="name"
                type="text"
                class="mt-1 block w-full"
                v-model="form.name"
                autocomplete="name"
              />
              <jet-input-error :message="form.errors.name" class="mt-2" />
            </div>

            <!-- Slug -->
            <div class="col-span-6 sm:col-span-4" v-if="category">
              <jet-label for="slug" value="slug" />
              <jet-input
                id="slug"
                type="text"
                class="mt-1 block w-full"
                :value="category.slug"
                autocomplete="slug"
                disabled
              />
            </div>

            <!-- category cover image -->
            <div class="col-span-6 sm:col-span-4">
              <!-- Profile Photo File Input -->
              <input
                type="file"
                class="hidden"
                ref="photo"
                @change="updatePhotoPreview"
              />

              <jet-label for="photo" value="Cover Image" />

              <!-- Current Profile Photo -->
              <div class="mt-2" v-show="!photoPreview">
                <img
                  :src="category ? category.cover_image : '/noimage.png'"
                  :alt="form.name"
                  class="rounded-sm object-cover"
                  style="width: 150px; height: 150px"
                />
              </div>

              <!-- New Profile Photo Preview -->
              <div class="mt-2" v-show="photoPreview">
                <span
                  class="block rounded-sm bg-cover bg-no-repeat bg-center"
                  style="width: 150px; height: 150px"
                  :style="'background-image: url(\'' + photoPreview + '\');'"
                >
                </span>
              </div>

              <jet-secondary-button
                class="mt-2 mr-2"
                type="button"
                @click.prevent="selectNewPhoto"
              >
                Select A Photo
              </jet-secondary-button>

              <jet-input-error :message="form.errors.photo" class="mt-2" />
            </div>
          </template>

          <template #actions>
            <jet-action-message :on="form.recentlySuccessful" class="mr-3">
              Saved.
            </jet-action-message>

            <jet-button
              :class="{ 'opacity-25': form.processing }"
              :disabled="form.processing"
            >
              Save
            </jet-button>

            <Link :href="route('categories.show')" class="ml-4">
              <jet-secondary-button :type="'button'"
                >Cancel</jet-secondary-button
              >
            </Link>
          </template>
        </jet-form-section>
      </div>
    </div>
  </app-layout>
</template>

<script>
import { defineComponent } from "vue";
import AppLayout from "@/Layouts/AppLayout.vue";
import JetButton from "@/Jetstream/Button.vue";
import JetFormSection from "@/Jetstream/FormSection.vue";
import JetInput from "@/Jetstream/Input.vue";
import JetInputError from "@/Jetstream/InputError.vue";
import JetLabel from "@/Jetstream/Label.vue";
import JetActionMessage from "@/Jetstream/ActionMessage.vue";
import JetSecondaryButton from "@/Jetstream/SecondaryButton.vue";
import { Link } from "@inertiajs/inertia-vue3";

export default defineComponent({
  components: {
    AppLayout,
    JetActionMessage,
    JetButton,
    JetFormSection,
    JetInput,
    JetInputError,
    JetLabel,
    JetSecondaryButton,
    Link,
  },
  props: ["category"],
  setup() {},
  data() {
    return {
      form: this.$inertia.form({
        name: this.category === undefined ? "" : this.category.name,
        photo: null,
      }),
      photoPreview: null,
    };
  },
  methods: {
    createOrUpdateCategory() {
      if (this.category === undefined) {
        if (this.$refs.photo) {
          this.form.photo = this.$refs.photo.files[0];
          this.form.post(route("categories.store"), {
            errorBag: "createOrUpdateCategory",
            preserveScroll: true,
          });
        }
      } else {
        let updateForm = this.$inertia.form({
          name: this.form.name,
        });

        if (this.$refs.photo) {
          updateForm.photo = this.$refs.photo.files[0];
        }

        updateForm.put(route("categories.update", { id: this.category.id }), {
          errorBag: "createOrUpdateCategory",
          preserveScroll: true,
        });
      }
    },
    selectNewPhoto() {
      this.$refs.photo.click();
    },

    updatePhotoPreview() {
      const photo = this.$refs.photo.files[0];

      if (!photo) return;

      const reader = new FileReader();

      reader.onload = (e) => {
        this.photoPreview = e.target.result;
      };

      reader.readAsDataURL(photo);
    },
    clearPhotoFileInput() {
      if (this.$refs.photo?.value) {
        this.$refs.photo.value = null;
      }
    },
  },
});
</script>
