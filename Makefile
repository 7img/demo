.DEFAULT_GOAL := help

%::
	make
	@echo "$(SOMECOLOR) > type one of the targets above$(NOCOLOR)"
	@echo

## start: Starts the application
start:
	@echo "Starting..."
	./scripts/docker-compose.sh --debug --build
	@echo "Done"

makefile: help
help: Makefile
	@echo "Choose a make command from the following:"
	@echo
	@sed -n 's/^##//p' $< | column -t -s ':' |  sed -e 's/^/ /'
	@echo
