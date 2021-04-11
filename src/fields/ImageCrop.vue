<template>
  <k-field v-bind="$props" :input="_uid" class="kirby-imagecrop-field">
    <div class="image" v-if="image">
      <k-grid>
        <k-column width="5/6">
          <vue-cropper
            ref="cropper"
            :view-mode="1"
            :dragMode="crop"
            :autoCrop="true"
            :zoomable="false"
            :movable="false"
            :data="value"
            :aspectRatio="aspectRatio"
            :src="image"
            alt="Source Image"
            :ready="ready"
            :crop="cropmove"
            :cropend="cropend"    
          ></vue-cropper>
        </k-column>
        <k-column width="1/6">
          <k-headline>Crop Properties</k-headline>
          <k-list>
            <k-list-item :image=true :icon="{type: '→', back: 'black', emoji: true}" :text="value.x"/>
            <k-list-item :image=true :icon="{type: '↓', back: 'black', emoji: true}" :text="value.y"/>
            <k-list-item :image=true :icon="{type: '↔️', back: 'black', emoji: true}" :text="value.width"/>
            <k-list-item :image=true :icon="{type: '↕️', back: 'black', emoji: true}" :text="value.height"/>
          </k-list>
        </k-column>
      </k-grid>
    </div>
    <k-box v-else>
      That's not an image!
    </k-box>
  </k-field>
</template>

<script>
import VueCropper from 'vue-cropperjs';
import 'cropperjs/dist/cropper.css';

export default {
  components: {
    VueCropper
  },
  props: {
    label: String,
    image: String,
    value: Object,
    minSize: Object,
    preserveAspectRatio: Boolean,
    isCropping: Boolean
  },
  computed: {
    data() {
      return this.value;
    },
    aspectRatio(){
      if(this.preserveAspectRatio){
        return this.minSize.width / this.minSize.height;
      } else {
        return NaN;
      }
    }
  },
  watch: {
    value: function(){
      if(!this.isCropping){
        this.$refs.cropper.setData(this.value);
      }
    }
  },
  methods: {
    cropmove(e){
      var
        update = false,
        data = this.$refs.cropper.getData(true);
      
      this.isCropping = true;

      if(data.width < this.minSize.width) {
        data.width = this.minSize.width;
        update = true;
      }
      if(data.height < this.minSize.height) {
        data.height = this.minSize.height;
        update = true;
      }

      this.value = data;
      if(update){
        this.$refs.cropper.setData(data);
      }
    },
    cropend(e){
      if(this.ready){
        this.value = this.$refs.cropper.getData(true);;
        this.isCropping = false;
        this.$emit("input", this.value);
      }
    },
    ready(){
      this.ready = true;
      this.isCropping = false;
    }
  }
};

</script>