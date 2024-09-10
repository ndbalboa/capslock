<template>
    <div class="scan-document">
      <h1>Scan Document</h1>
      <input type="file" @change="handleFileUpload" accept="image/*" />
      <button @click="scanDocument">Scan Document</button>
      <div v-if="scannedImage">
        <img :src="scannedImage" alt="Scanned Document" />
      </div>
      <div v-if="ocrResult">
        <h2>OCR Result</h2>
        <p>{{ ocrResult }}</p>
      </div>
      <div v-if="documentCategory">
        <h2>Document Category</h2>
        <p>{{ documentCategory }}</p>
      </div>
    </div>
  </template>
  
  <script>
  import Tesseract from 'tesseract.js';
  import NaiveBayes from 'naive-bayes';
  
  export default {
    data() {
      return {
        scannedImage: null,
        ocrResult: '',
        documentCategory: '',
        // Sample training data for Naive Bayes
        classifier: new NaiveBayes(),
        categories: {
          'invoice': ['invoice', 'receipt', 'bill'],
          'report': ['report', 'summary', 'analysis'],
          // Add more categories as needed
        }
      };
    },
    methods: {
      handleFileUpload(event) {
        const file = event.target.files[0];
        if (file) {
          const reader = new FileReader();
          reader.onload = (e) => {
            this.scannedImage = e.target.result;
          };
          reader.readAsDataURL(file);
        }
      },
      scanDocument() {
        if (this.scannedImage) {
          Tesseract.recognize(
            this.scannedImage,
            'eng',
            { logger: info => console.log(info) }
          ).then(({ data: { text } }) => {
            this.ocrResult = text;
            this.categorizeDocument(text);
          }).catch(error => {
            console.error('OCR error:', error);
          });
        }
      },
      categorizeDocument(text) {
        const trainingData = this.getTrainingData();
        this.classifier.train(trainingData);
        const category = this.classifier.predict(text);
        this.documentCategory = category;
      },
      getTrainingData() {
        const data = [];
        for (const [category, keywords] of Object.entries(this.categories)) {
          keywords.forEach(keyword => {
            data.push({ text: keyword, category });
          });
        }
        return data;
      }
    },
    mounted() {
      // Initialize the Naive Bayes classifier with sample data
      this.getTrainingData().forEach(item => {
        this.classifier.learn(item.text, item.category);
      });
    }
  };
  </script>
  
  <style scoped>
  .scan-document {
    max-width: 600px;
    margin: auto;
    padding: 20px;
  }
  img {
    max-width: 100%;
    height: auto;
  }
  </style>
  