<script setup>

import { DwtUIOperations } from './tools/dwtUIOperations'

import { RemoveCurrentPageDialog } from './tools/removeCurrentPageDialog'
import { RemoveAllPagesDialog } from './tools/removeAllPagesDialog'

const props = defineProps({
  dwtUtil: {
    required: true,
    type: DwtUIOperations
  }
});

const dlgRemoveCurrent = new RemoveCurrentPageDialog(props.dwtUtil);
const dlgRemoveAll = new RemoveAllPagesDialog(props.dwtUtil);

function clickbtnZoomOut() {
  props.dwtUtil?.onclickZoomOut();
}
function clickbtnZoomIn() {
  props.dwtUtil?.onclickZoomIn();
}
function clickbtnOrigSize() {
  props.dwtUtil?.onclickOrigSize();
}
function clickbtnFitWindow() {
  props.dwtUtil?.onclickFitWindow();
}
function clickbtnRotateLeft() {
  props.dwtUtil?.onclickRotateLeft();
}
function clickbtnCrop() {
  props.dwtUtil?.onclickCrop();
}
function clickbtnShowImageEditor() {
  props.dwtUtil?.onclickShowImageEditor();
}
function clickbtnSelect() {
  props.dwtUtil?.onclickSelect();
}
function clickbtnHand() {
  props.dwtUtil?.onclickHandButton();
}

function clickbtnRemoveCurrentImage() {
  dlgRemoveCurrent?.remove();
}
function clickbtnRemoveAllImages() {
  dlgRemoveAll?.removeAll();
}
</script>

<template>
  <div id="divEdit">
    <ul class="operateGrp">
      <li>
        <div class="menuIcon RemoveSelectedImages" style="margin-left: 5px;" title="Remove current page"
          alt="Remove current page" id="DW_btnRemoveCurrentImage" v-on:click="clickbtnRemoveCurrentImage();"></div>
      </li>
      <li>
        <div class="menuIcon RemoveAllImages" title="Remove all pages" alt="Remove all pages" id="DW_btnRemoveAllImages"
          v-on:click="clickbtnRemoveAllImages();"></div>
      </li>
      <li style="width:90px;"></li>
      <li class="lblShowCurrentImage"><input type="text" size="2" id="DW_CurrentImage" readonly="readonly">/<input
          type="text" size="2" id="DW_TotalImage" readonly="readonly"></li>
      <li class="lblZoom">
        <ul>
          <li style="width:25%">
            <div class="menuIcon ZoomOut" title="Zoom out" alt="Zoom out" id="btnZoomOut" v-on:click="clickbtnZoomOut();">
            </div>
          </li>
          <li style="width:50%"><input type="text" id="DW_spanZoom" readonly="readonly"></li>
          <li style="width:25%">
            <div class="menuIcon ZoomIn" title="Zoom in" alt="Zoom in" id="btnZoomIn" v-on:click="clickbtnZoomIn();">
            </div>
          </li>
        </ul>
      </li>
      <li>
        <div style="margin-left: 10px;" class="menuIcon OrigSize" title="1:1" alt="1:1" id="btnOrigSize"
          v-on:click="clickbtnOrigSize();"></div>
        <div class="menuIcon FitWindow" title="Fit To Window" alt="Fit To Window" id="btnFitWindow" style="display:none"
          v-on:click="clickbtnFitWindow()"></div>
      </li>
      <li style="width:50px;"></li>
      <li>
        <div class="menuIcon RotateLeft" title="Rotate left" alt="Rotate left" id="btnRotateL"
          v-on:click="clickbtnRotateLeft()"></div>
      </li>
      <li>
        <div class="menuIcon grayimg Crop" title="Please select an area to crop." alt="Please select an area to crop."
          id="btnCropGray"></div>
        <div class="menuIcon Crop" title="Crop" alt="Crop" id="btnCrop" style="display:none" v-on:click="clickbtnCrop();">
        </div>
      </li>
      <li>
        <div class="menuIcon ShowEditor" title="Show image editor" alt="Show image editor" id="btnShowImageEditor"
          v-on:click="clickbtnShowImageEditor();"></div>
      </li>
      <li style="margin-top:0">
        <div class="menuIcon SelectSelected" title="Select" alt="Select" id="btnSelect_selected"></div>
        <div class="menuIcon Select" style="display:none;" title="Select" alt="Select" id="btnSelect"
          v-on:click="clickbtnSelect();"></div>
      </li>
      <li style="margin-top:0">
        <div class="menuIcon HandSelected" style="display:none;" title="Hand" alt="Hand" id="btnHand_selected">
        </div>
        <div class="menuIcon Hand" title="Hand" alt="Hand" id="btnHand" v-on:click="clickbtnHand();"></div>
      </li>
    </ul>
  </div>
</template>
