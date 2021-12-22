<template>
  <app-layout title="Manage Shows">
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Manage Slider Images
      </h2>
    </template>
    <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
      <jet-form-section @submitted="createUpdateItem">
        <template #title>
          {{
            sliderItem == undefined
              ? "Create new Slider Image"
              : "Edit Slider Image"
          }}
        </template>
        <template #description>
          <ul>
            <li>• Create or Update the slider image.</li>
            <li>• Image resolution is 630px X 470px</li>
            <li>• Image size must lower equal or lover than 1mb.</li>
            <li>• Image formt should be .png, .jpg or .jpeg</li>
          </ul>
        </template>

        <template #form>
          <!-- episode cover image -->
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
                :src="
                  sliderItem ? sliderItem.slider_image_thumb : '/noimage.png'
                "
                :alt="form.title"
                class="rounded-sm object-cover"
                style="width: 330px; height: 170px"
              />
            </div>

            <!-- New Profile Photo Preview -->
            <div class="mt-2" v-show="photoPreview">
              <span
                class="block rounded-sm bg-cover bg-no-repeat bg-center"
                style="width: 330px; height: 170px"
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
          <!-- Title -->
          <div class="col-span-6 sm:col-span-4">
            <jet-label for="title" value="Image Title" />
            <jet-input
              id="title"
              type="text"
              class="mt-1 block w-full"
              v-model="form.title"
              autocomplete="title"
              placeholder="Image title"
            />
            <jet-input-error :message="form.errors.title" class="mt-2" />
          </div>

          <!-- description -->
          <div class="col-span-6 sm:col-span-4">
            <jet-label for="description" value="Description" />
            <jet-text-area
              id="description"
              type="text"
              class="mt-1 block w-full"
              v-model="form.description"
              autocomplete="description"
              cols="5"
              rows="10"
              placeholder="Slider Image Description"
            />

            <jet-input-error :message="form.errors.description" class="mt-2" />
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

          <Link :href="route('sliderImages.show')" class="ml-4">
            <jet-secondary-button :type="'button'">Cancel</jet-secondary-button>
          </Link>
        </template>
      </jet-form-section>
    </div>
  </app-layout>
</template>

<script>
import { defineComponent } from "vue";
import AppLayout from "@/Layouts/AppLayout.vue";
import JetButton from "@/Jetstream/Button.vue";
import JetFormSection from "@/Jetstream/FormSection.vue";
import JetInput from "@/Jetstream/Input.vue";
import JetTextArea from "@/Jetstream/InputArea.vue";
import JetInputError from "@/Jetstream/InputError.vue";
import JetLabel from "@/Jetstream/Label.vue";
import JetActionMessage from "@/Jetstream/ActionMessage.vue";
import JetSecondaryButton from "@/Jetstream/SecondaryButton.vue";
import { Link } from "@inertiajs/inertia-vue3";

export default defineComponent({
  props: ["sliderItem"],
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
    JetTextArea,
  },
  mounted() {
    if (this.sliderItem) {
      this.form.title = this.sliderItem.title;
      this.form.description = this.sliderItem.description;
    }
  },
  data() {
    return {
      form: this.$inertia.form({
        title: "",
        description: "",
        photo: null,
      }),
      photoPreview: null,
    };
  },
  methods: {
    createUpdateItem() {
      if (this.sliderItem === undefined) {
        if (this.$refs.photo) {
          this.form.photo = this.$refs.photo.files[0];
          this.form.post(route("sliderImages.store"), {
            errorBag: "createUpdateSliderItem",
            preserveScroll: true,
          });
        }
      } else {
        if (this.$refs.photo) {
          this.form.photo = this.$refs.photo.files[0];
        } else {
          delete this.form.photo;
        }

        this.form.post(
          route("sliderImages.update", { id: this.sliderItem.id }),
          {
            errorBag: "createUpdateSliderItem",
            preserveScroll: true,
          }
        );
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

