<template>
  <div>
  <multiselect 
    
    v-model="value" 
    :options="options" 
    :multiple="false" 
    group-values="data" 
    group-label="level" 
    :group-select="false" 
    :allow-empty="false"
    placeholder="Select Level" 
    track-by="name" 
    label="name"
    @input = "emitToParent"
    :disabled  = disabled
    >
      <span slot="noResult">Oops! No elements found. Consider changing the search query.</span>
    </multiselect>
    <input type="hidden" name="office_id" id="office_id" :value="value.id" @change="emitToParent">
    
  
  </div>
</template>

<script>
import Multiselect from 'vue-multiselect'

export default {
  components: {
    Multiselect
  },
  props: ['name','default_value','list_level','enable_after_load'],
  created(){
      
      if(this.list_level==null){
          this.fetchAllLevels(this.default_value);
      }else{
          this.fetchListByLevel(this.list_level)
      }
      
    
  },
  data () {
    return {
        lists: null,
        options: [],
        value: [],
        disabled :true
    }
  },
  methods: {
    emitToParent(){
      if(this.value!=null){
        
        console.log(this.value);
        this.$emit('officeSelected', this.value);
      }
    },
    fetchAllLevels(selected){
      axios.get('/usr/branches')
        .then(res=>{
          this.options=res.data
          this.disabled =false
            if(this.default_value!==undefined || this.default_value == null){
              
              this.options.filter( obj => {
                var item = obj.data.filter(office => {
                   office.id == this.default_value ? this.value = office : ''
                })
              })
              
              if(selected !== ""){
                this.value = JSON.parse(selected)
                
                
              }else{
                
              }

              
          }
        })
    },
    fetchListByLevel(level){
        axios.get('/usr/branches?level='+level)
        .then(res=>{
          this.options=res.data
            if(this.default_value!==undefined){
              this.options.filter( obj => {
                var item = obj.data.filter(office => {
                   office.id == this.default_value ? this.value = office : ''
                })
              }) 
              this.disabled =false
          }
        })
    }
  }
}

</script>
<style>
    @import "~vue-multiselect/dist/vue-multiselect.min.css";
    

    .is-invalid .multiselect__tags {
      border: 1px solid red !important;
    }

</style>

