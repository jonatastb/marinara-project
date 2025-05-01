<template>

     <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
       <div class="flex h-full flex-1 flex-col justify-center items-center gap-4 rounded-xl p-4">
        <h1 class="text-xl font-medium">
          Auditoria de Fatura
        </h1>
        <div class="w-1/2 flex flex-col gap-4 ">
          <div class="flex flex-col gap-4">
            <div class="flex gap-2">
              <Input type="file" @change="onFileChange"/> 
            </div>
            <div class="flex gap-2">
              <Input type="number"  @change="onMinutesChange" placeholder="Minutos de diferença" min="1"  />
            </div>
          </div>
         
          <Button class="cursor-pointer" @click="sendFile">
            Validar
          </Button>
          <Button class="cursor-pointer" :disabled="!grupos" @click="downloadFile">
            Download
          </Button>
           <span v-if="mensagem">
            {{ mensagem }}
          </span>
        </div>
       </div>
    </AppLayout>
</template>
<script setup>
import AppLayout from '@/layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import { Input } from '@/components/ui/input/index';
import { Button } from '@/components/ui/button/index';
import { ref } from 'vue';
import axios  from 'axios';

const file = ref(null);
const minutes = ref(null);
const grupos = ref(null);
const mensagem = ref(null);

const onFileChange = (event) => {
  file.value = event.target.files[0];
};
const onMinutesChange = (event) => {
  minutes.value = Number(event.target.value);
};

const sendFile = () => {
  mensagem.value = 'Carregando';
  const formData = new FormData();
  formData.append('file', file.value);
  formData.append('minutos', minutes.value);

  axios.post(route('auditoria.processar'), formData, {
    headers: {
      'Content-Type': 'multipart/form-data',
    },
  }).then((response) => {
    grupos.value = response.data.grupos;
    mensagem.value = null;
  }).catch((error) => {
    mensagem.value = 'Erro';
    console.error(error);
  });
};

const downloadFile = (url) => {
  mensagem.value = 'Carregando';
   const gruposString = encodeURIComponent(JSON.stringify(grupos.value));

  axios.get(route('auditoria.download') + `?grupos=${gruposString}`, {
    responseType: 'blob'
  }).then(response => {
    mensagem.value = null;
    const url = window.URL.createObjectURL(new Blob([response.data]));
    const link = document.createElement('a');
    link.href = url;
    link.setAttribute('download', 'auditoria_exportada.xlsx');
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
  }).catch((error) => {
    mensagem.value = 'Erro';
    console.error(error);
  });
};
</script>