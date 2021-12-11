#!/usr/bin/make -f
IMAGE := laravel
VERSION := latest
CONTAINER_NAME := eLearning-backend
.PHONY: all build  run clean

# ------------------------------------------------------------------------------

all: build

# «Ø¸m image
build:
	docker build -t=$(IMAGE):$(VERSION) .

# °õ¦æ container
run:
	docker run -d --name $(CONTAINER_NAME) -p 8000:8000 $(IMAGE):$(VERSION)
clean:
	docker container rm -f $(CONTAINER_NAME) || true
	docker image rm -f $(IMAGE):$(VERSION) || true