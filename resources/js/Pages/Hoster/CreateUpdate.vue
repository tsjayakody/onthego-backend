<template>
  <app-layout title="Manage Hosters">
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Manage Hosters
      </h2>
    </template>

    <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
      <jet-form-section @submitted="createUpdateHoster">
        <template #title>
          {{ hoster == undefined ? "Create new hoster" : "Edit hoster" }}
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
          <div class="col-span-6 sm:col-span-4" v-if="hoster">
            <jet-label for="slug" value="slug" />
            <jet-input
              id="slug"
              type="text"
              class="mt-1 block w-full"
              :value="hoster.slug"
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

            <jet-label for="photo" value="Profile Image" />

            <!-- Current Profile Photo -->
            <div class="mt-2" v-show="!photoPreview">
              <img
                :src="hoster ? hoster.hoster_image : '/noimage.png'"
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

          <!-- email -->
          <div class="col-span-6 sm:col-span-4">
            <jet-label for="email" value="E-Mail" />
            <jet-input
              id="email"
              type="text"
              class="mt-1 block w-full"
              v-model="form.email"
              autocomplete="email"
            />

            <jet-input-error :message="form.errors.email" class="mt-2" />
          </div>

          <!-- designation -->
          <div class="col-span-6 sm:col-span-4">
            <jet-label for="designation" value="Designation" />
            <jet-input
              id="designation"
              type="text"
              class="mt-1 block w-full"
              v-model="form.designation"
              autocomplete="designation"
            />

            <jet-input-error :message="form.errors.designation" class="mt-2" />
          </div>

          <!-- contact number -->
          <div class="col-span-6 sm:col-span-4">
            <jet-label for="contact_number" value="Contact Number" />
            <jet-input
              id="contact_number"
              type="text"
              class="mt-1 block w-full"
              v-model="form.contact_number"
              autocomplete="contact_number"
            />

            <jet-input-error :message="form.errors.contact_number" class="mt-2" />
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
            />

            <jet-input-error :message="form.errors.description" class="mt-2" />
          </div>

          <!-- facebook -->
          <div class="col-span-6 sm:col-span-4">
            <jet-label for="facebook" value="Facebook" />
            <jet-input
              id="facebook"
              type="text"
              class="mt-1 block w-full"
              v-model="form.facebook"
              autocomplete="facebook"
            />

            <jet-input-error :message="form.errors.facebook" class="mt-2" />
          </div>

          <!-- twitter -->
          <div class="col-span-6 sm:col-span-4">
            <jet-label for="twitter" value="Twitter" />
            <jet-input
              id="twitter"
              type="text"
              class="mt-1 block w-full"
              v-model="form.twitter"
              autocomplete="twitter"
            />

            <jet-input-error :message="form.errors.twitter" class="mt-2" />
          </div>

          <!-- linkedin -->
          <div class="col-span-6 sm:col-span-4">
            <jet-label for="linkedin" value="Linkedin" />
            <jet-input
              id="linkedin"
              type="text"
              class="mt-1 block w-full"
              v-model="form.linkedin"
              autocomplete="linkedin"
            />

            <jet-input-error :message="form.errors.linkedin" class="mt-2" />
          </div>

          <!-- youtube -->
          <div class="col-span-6 sm:col-span-4">
            <jet-label for="youtube" value="Youtube" />
            <jet-input
              id="youtube"
              type="text"
              class="mt-1 block w-full"
              v-model="form.youtube"
              autocomplete="youtube"
            />

            <jet-input-error :message="form.errors.youtube" class="mt-2" />
          </div>

          <!-- instagram -->
          <div class="col-span-6 sm:col-span-4">
            <jet-label for="instagram" value="Instagram" />
            <jet-input
              id="instagram"
              type="text"
              class="mt-1 block w-full"
              v-model="form.instagram"
              autocomplete="instagram"
            />

            <jet-input-error :message="form.errors.instagram" class="mt-2" />
          </div>

          <!-- anchor -->
          <div class="col-span-6 sm:col-span-4">
            <jet-label for="anchor" value="Anchor" />
            <jet-input
              id="anchor"
              type="text"
              class="mt-1 block w-full"
              v-model="form.anchor"
              autocomplete="anchor"
            />

            <jet-input-error :message="form.errors.anchor" class="mt-2" />
          </div>

          <!-- past works -->
          <div class="col-span-6 sm:col-span-4">
            <jet-label for="past_works" value="Past Works" />
            <jet-text-area
              id="past_works"
              type="text"
              class="mt-1 block w-full"
              v-model="form.past_works"
              autocomplete="past_works"
              cols="5"
              rows="10"
            />

            <jet-input-error :message="form.errors.past_works" class="mt-2" />
          </div>

          <!-- is approved -->
          <div class="col-span-6 sm:col-span-4">
            <jet-label for="is_approved" value="Approved" />
            <toggle-switch
              class="mt-1"
              :value="form.is_approved"
              @input="toggleButton"
              id="is_approved"
            />

            <jet-input-error :message="form.errors.is_approved" class="mt-2" />
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

          <Link :href="route('hosters.show')" class="ml-4">
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
import JetInputError from "@/Jetstream/InputError.vue";
import JetLabel from "@/Jetstream/Label.vue";
import JetActionMessage from "@/Jetstream/ActionMessage.vue";
import JetSecondaryButton from "@/Jetstream/SecondaryButton.vue";
import JetTextArea from "@/Jetstream/InputArea.vue";
import ToggleSwitch from "@/Jetstream/ToggleSwitch";
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
    JetTextArea,
    ToggleSwitch,
  },

  props: ["hoster"],

  data() {
    return {
      form: this.$inertia.form({
        name: this.hoster ? this.hoster.name : "",
        email: this.hoster ? this.hoster.email : "",
        contact_number: this.hoster ? this.hoster.contact_number : "",
        description: this.hoster ? this.hoster.description : "",
        linkedin: this.hoster ? this.hoster.linkedin : "",
        facebook: this.hoster ? this.hoster.facebook : "",
        twitter: this.hoster ? this.hoster.twitter : "",
        instagram: this.hoster ? this.hoster.instagram : "",
        anchor: this.hoster ? this.hoster.anchor : "",
        past_works: this.hoster ? this.hoster.past_works : "",
        youtube: this.hoster ? this.hoster.youtube : "",
        designation: this.hoster ? this.hoster.designation : "",
        is_approved: this.hoster ? this.hoster.is_approved : false,
        photo: null,
      }),
      photoPreview: null,
    };
  },

  methods: {
    createUpdateHoster() {
      if (this.hoster === undefined) {
        if (this.$refs.photo) {
          this.form.photo = this.$refs.photo.files[0];
          this.form.post(route("hosters.store"), {
            errorBag: "createUpdateHoster",
            preserveScroll: true,
          });
        }
      } else {
        if (this.$refs.photo) {
          this.form.photo = this.$refs.photo.files[0];
        } else {
            delete(this.form.photo);
        }

        this.form.put(route("hosters.update", { id: this.hoster.id }), {
          errorBag: "createUpdateHoster",
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

    toggleButton() {
      this.form.is_approved = !this.form.is_approved;
    },
  },
});
</script>
