<field-uniqueid>
    <div class="uk-position-relative field-uniqueid-container">
        <div class="uk-width-1-1">
            <i if="{!value}">Save entry to generate a unique id</i>
            <label if="{value}">{value}</label>
        </div>
    </div>
    <script>
        this.value = "";

        this.on('mount', function () {
            this.update();
        });

        $updateValue(value) {
            var entry = this.getEntry();
            if (entry && entry._id) {
                this.value = value;
            } else {
                this.$setValue(null);
            }
            this.update();
        }

        getEntry() {
            var parent = this.$boundTo;
            while (!parent.entry) {
                parent = parent.$boundTo;
                if (!parent) return null;
            }
            return parent.entry;
        }
    </script>
</field-uniqueid>
