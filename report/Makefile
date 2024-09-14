# Variables
OUTPUT_FILE = Report.pdf
PANDOC_OPTS = -N --eol=crlf --highlight-style=tango

all: $(OUTPUT_FILE)

# To generate output file
$(OUTPUT_FILE): Report.md img/*
	echo "Creating ${OUTPUT_FILE}..."
	pandoc Report.md -o $(OUTPUT_FILE) $(PANDOC_OPTS)

# Remove PDF
clean:
	echo "Removing ${OUTPUT_FILE}"
	rm -f $(OUTPUT_FILE)

# Regola per rimuovere file temporanei (se presenti)
cleanall: clean
	rm -f *.log *.tex

.PHONY: all clean cleanall
