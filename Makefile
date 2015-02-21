SHELL = /bin/bash
.PHONY: docs preview

docs:
	@echo "Generating documentation..."
	couscous generate
	mkdir -p ".couscous/generated/docs/images"
	cp "docs/images/form-generation.gif" ".couscous/generated/docs/images/"

preview:
	php -S localhost:8000 -t ./.couscous/generated
